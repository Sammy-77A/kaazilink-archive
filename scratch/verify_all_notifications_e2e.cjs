const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const LOG_PATH = path.resolve(__dirname, '../storage/logs/laravel.log');
const BASE_URL = 'http://127.0.0.1:8000';

function getLogLength() {
    if (!fs.existsSync(LOG_PATH)) return 0;
    return fs.statSync(LOG_PATH).size;
}

function getNewLogEntries(startOffset) {
    if (!fs.existsSync(LOG_PATH)) return '';
    const currentSize = fs.statSync(LOG_PATH).size;
    if (currentSize <= startOffset) return '';
    const buffer = Buffer.alloc(currentSize - startOffset);
    const fd = fs.openSync(LOG_PATH, 'r');
    fs.readSync(fd, buffer, 0, currentSize - startOffset, startOffset);
    fs.closeSync(fd);
    return buffer.toString('utf8');
}

function extractEmailsFromLog(logChunk) {
    const emails = [];
    const messageBlocks = logChunk.split(/From:\s+/i);
    for (let i = 1; i < messageBlocks.length; i++) {
        const block = 'From: ' + messageBlocks[i];
        const toMatch = block.match(/To:\s+([^\r\n]+)/i);
        const subjectMatch = block.match(/Subject:\s+([^\r\n]+)/i);
        
        // Extract clean text
        let textContent = block;
        const htmlParts = block.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
        if (htmlParts) {
            textContent = htmlParts[1].replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
        }

        emails.push({
            to: toMatch ? toMatch[1].trim() : 'Unknown',
            subject: subjectMatch ? subjectMatch[1].trim() : 'Unknown',
            raw: block,
            cleanBody: textContent.substring(0, 800)
        });
    }
    return emails;
}

async function loginUser(page, context, email, password) {
    await context.clearCookies();
    await page.goto(`${BASE_URL}/login`);
    await page.fill('input[wire\\:model="email"]', email);
    await page.fill('input[wire\\:model="password"]', password);
    await page.click('button[type="submit"]');
    await page.waitForURL(`${BASE_URL}/`);
}

async function run() {
    console.log('=== STARTING COMPLETE NOTIFICATION E2E BROWSER VERIFICATION ===\n');
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    const results = {};

    try {
        // =====================================================================
        // TEST 7: Registration -> Exactly ONE email (branded verification)
        // =====================================================================
        console.log('--- TEST 7: New user registers -> exactly ONE branded verification email ---');
        await context.clearCookies();
        let offset = getLogLength();
        const testUserEmail = `otieno_${Date.now()}@example.com`;
        await page.goto(`${BASE_URL}/register`);
        await page.fill('input[wire\\:model="name"]', 'Otieno Ouma');
        await page.fill('input[wire\\:model="email"]', testUserEmail);
        await page.fill('input[wire\\:model="password"]', 'Password123!');
        await page.fill('input[wire\\:model="password_confirmation"]', 'Password123!');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/email/verify');

        await new Promise(r => setTimeout(r, 1500));
        let logChunk = getNewLogEntries(offset);
        let emails = extractEmailsFromLog(logChunk);

        results.test7 = {
            emailCount: emails.length,
            to: emails[0]?.to,
            subject: emails[0]?.subject,
            sampleBody: emails[0]?.cleanBody,
            raw: emails[0]?.raw,
            passed: emails.length === 1 && emails[0]?.subject.includes('Karibu Kaazilink') && emails[0]?.subject.includes('Verify Your Account')
        };
        console.log(`Sent count: ${emails.length}`);
        console.log(`To: ${results.test7.to}`);
        console.log(`Subject: ${results.test7.subject}\n`);

        const verifyUrlMatch = emails[0]?.raw.match(/http:\/\/127\.0\.0\.1:8000\/email\/verify\/[^\s"'>]+/);
        const verificationUrl = verifyUrlMatch ? verifyUrlMatch[0].replace(/&amp;/g, '&') : null;

        // =====================================================================
        // TEST 8: Email verified -> WelcomeNotification received
        // =====================================================================
        console.log('--- TEST 8: User completes email verification -> WelcomeNotification ---');
        if (verificationUrl) {
            offset = getLogLength();
            await page.goto(verificationUrl);
            await page.waitForURL(`${BASE_URL}/`);
            await new Promise(r => setTimeout(r, 1500));
            logChunk = getNewLogEntries(offset);
            emails = extractEmailsFromLog(logChunk);

            results.test8 = {
                emailCount: emails.length,
                to: emails[0]?.to,
                subject: emails[0]?.subject,
                sampleBody: emails[0]?.cleanBody,
                raw: emails[0]?.raw,
                passed: emails.length === 1 && emails[0]?.subject.includes('Karibu Kaazilink') && emails[0]?.subject.includes('Account Verified & Active')
            };
            console.log(`Sent count: ${emails.length}`);
            console.log(`To: ${results.test8.to}`);
            console.log(`Subject: ${results.test8.subject}\n`);
        } else {
            results.test8 = { passed: false, error: 'Could not extract verification URL' };
        }

        // =====================================================================
        // TEST 1: Client books a provider -> Provider notified
        // =====================================================================
        console.log('--- TEST 1: Client books provider -> provider notified with rate & date ---');
        await loginUser(page, context, 'client_mary@kaazilink.com', 'password123');

        // Open provider profile 15 (Samuel Mwangi)
        await page.goto(`${BASE_URL}/provider/15`);
        offset = getLogLength();
        await page.click('button:has-text("Book Now")');
        const requestBookingBtn = page.locator('button:has-text("Request Booking")');
        await requestBookingBtn.waitFor({ state: 'visible', timeout: 10000 });
        await new Promise(r => setTimeout(r, 800));

        // Submit Booking
        await requestBookingBtn.click();
        await new Promise(r => setTimeout(r, 2500));

        logChunk = getNewLogEntries(offset);
        emails = extractEmailsFromLog(logChunk);
        results.test1 = {
            emailCount: emails.length,
            to: emails[0]?.to,
            subject: emails[0]?.subject,
            sampleBody: emails[0]?.cleanBody,
            raw: emails[0]?.raw,
            passed: emails.length === 1 && emails[0]?.subject.includes('New Booking Request from Mary Njeri')
        };
        console.log(`Sent count: ${emails.length}`);
        console.log(`To: ${results.test1.to}`);
        console.log(`Subject: ${results.test1.subject}\n`);

        // =====================================================================
        // TEST 2A: Provider marks booking completed -> Client notified to review
        // =====================================================================
        console.log('--- TEST 2A: Provider marks booking completed -> client notified ---');
        await loginUser(page, context, 'pro_samuel@kaazilink.com', 'password123');

        await page.goto(`${BASE_URL}/provider/dashboard`);
        await page.click('button:has-text("Bookings")');
        await new Promise(r => setTimeout(r, 1200));

        offset = getLogLength();
        const completeBtn = page.locator('button:has-text("Mark Completed")').first();
        if (await completeBtn.count() > 0) {
            await completeBtn.click();
            await new Promise(r => setTimeout(r, 2500));
        }

        logChunk = getNewLogEntries(offset);
        emails = extractEmailsFromLog(logChunk);
        results.test2a = {
            emailCount: emails.length,
            to: emails[0]?.to,
            subject: emails[0]?.subject,
            sampleBody: emails[0]?.cleanBody,
            raw: emails[0]?.raw,
            passed: emails.length === 1 && emails[0]?.subject.includes('Your Kaazilink Service with Samuel Mwangi is Complete')
        };
        console.log(`Sent count: ${emails.length}`);
        console.log(`To: ${results.test2a.to}`);
        console.log(`Subject: ${results.test2a.subject}\n`);

        // =====================================================================
        // TEST 6: Client leaves a review -> Provider notified with rating & comment
        // =====================================================================
        console.log('--- TEST 6: Client leaves review -> provider notified with stars & comment ---');
        await loginUser(page, context, 'client_mary@kaazilink.com', 'password123');

        await page.goto(`${BASE_URL}/dashboard/bookings`);
        const reviewBtn = page.locator('button:has-text("Leave Review")').first();
        if (await reviewBtn.count() > 0) {
            await reviewBtn.click();
            const submitReviewBtn = page.locator('button:has-text("Submit Review")');
            await submitReviewBtn.waitFor({ state: 'visible', timeout: 10000 });
            await new Promise(r => setTimeout(r, 800));
            const selectEl = page.locator('select').first();
            if (await selectEl.count() > 0) {
                await selectEl.selectOption('5');
            }
            await page.locator('textarea').first().fill('Samuel did exceptional plumbing work, very respectful and on time.');
            offset = getLogLength();
            await submitReviewBtn.click();
            await new Promise(r => setTimeout(r, 2500));

            logChunk = getNewLogEntries(offset);
            emails = extractEmailsFromLog(logChunk);
            results.test6 = {
                emailCount: emails.length,
                to: emails[0]?.to,
                subject: emails[0]?.subject,
                sampleBody: emails[0]?.cleanBody,
                raw: emails[0]?.raw,
                passed: emails.length === 1 && emails[0]?.subject.includes('New 5-Star Review from Mary Njeri')
            };
            console.log(`Sent count: ${emails.length}`);
            console.log(`To: ${results.test6.to}`);
            console.log(`Subject: ${results.test6.subject}\n`);
        }

        // =====================================================================
        // TEST 2B: Client marks booking completed -> Provider notified
        // =====================================================================
        console.log('--- TEST 2B: Client marks booking completed -> provider notified ---');
        // Create an accepted booking in DB for client 16 and provider 15
        execSync('php artisan tinker --execute="\\App\\Models\\Booking::create([\'client_id\'=>16, \'provider_id\'=>15, \'service_slug\'=>\'house-cleaning\', \'price\'=>2500, \'status\'=>\'accepted\']);"');

        await page.goto(`${BASE_URL}/dashboard/bookings`);
        const clientCompleteBtn = page.locator('button:has-text("Mark Completed")').first();
        if (await clientCompleteBtn.count() > 0) {
            offset = getLogLength();
            await clientCompleteBtn.click();
            await new Promise(r => setTimeout(r, 2500));

            logChunk = getNewLogEntries(offset);
            emails = extractEmailsFromLog(logChunk);
            results.test2b = {
                emailCount: emails.length,
                to: emails[0]?.to,
                subject: emails[0]?.subject,
                sampleBody: emails[0]?.cleanBody,
                raw: emails[0]?.raw,
                passed: emails.length === 1 && emails[0]?.subject.includes('Booking Marked Completed by Mary Njeri')
            };
            console.log(`Sent count: ${emails.length}`);
            console.log(`To: ${results.test2b.to}`);
            console.log(`Subject: ${results.test2b.subject}\n`);
        }

        // =====================================================================
        // TEST 3: Provider submits KYC -> Admin notified
        // =====================================================================
        console.log('--- TEST 3: Provider submits KYC -> admin notified ---');
        await loginUser(page, context, 'kyc_test_user@kaazilink.com', 'password123');

        await page.goto(`${BASE_URL}/provider/onboarding`);
        // Navigate to Step 4
        await page.click('button:has-text("4. KYC Verification")');
        await new Promise(r => setTimeout(r, 1200));

        const idFrontInput = page.locator('input[wire\\:model="idFront"]');
        const idBackInput = page.locator('input[wire\\:model="idBack"]');
        const kraPinInput = page.locator('input[wire\\:model="kraPin"]');

        if (await idFrontInput.count() > 0) {
            await idFrontInput.setInputFiles(path.resolve(__dirname, 'id_front.jpg'));
            await page.waitForTimeout(1000);
            await idBackInput.setInputFiles(path.resolve(__dirname, 'id_back.jpg'));
            await page.waitForTimeout(1000);
            await kraPinInput.setInputFiles(path.resolve(__dirname, 'kra_pin.jpg'));
            await page.waitForTimeout(2000);

            offset = getLogLength();
            await page.click('button:has-text("Submit KYC Documents for Review")');
            await page.waitForSelector(':has-text("KYC documents submitted successfully")', { timeout: 15000 });
            await new Promise(r => setTimeout(r, 1000));

            logChunk = getNewLogEntries(offset);
            emails = extractEmailsFromLog(logChunk);
            results.test3 = {
                emailCount: emails.length,
                to: emails[0]?.to,
                subject: emails[0]?.subject,
                sampleBody: emails[0]?.cleanBody,
                raw: emails[0]?.raw,
                passed: emails.length === 1 && emails[0]?.subject.includes('New KYC Verification Pending Review')
            };
            console.log(`Sent count: ${emails.length}`);
            console.log(`To: ${results.test3.to}`);
            console.log(`Subject: ${results.test3.subject}\n`);
        }

        // =====================================================================
        // TEST 4: Admin approves KYC -> Provider notified
        // =====================================================================
        console.log('--- TEST 4: Admin approves KYC -> provider notified ---');
        await loginUser(page, context, 'admin@kaazilink.com', 'password123');

        await page.goto(`${BASE_URL}/admin/kyc`);
        const kipchogeRow = page.locator('tr:has-text("Kipchoge Keino")').first();
        if (await kipchogeRow.count() > 0) {
            await kipchogeRow.locator('a:has-text("Review")').click();
            await page.waitForURL('**/admin/kyc/*');
            await page.waitForSelector('button:has-text("Approve KYC")');

            offset = getLogLength();
            await page.click('button:has-text("Approve KYC")');
            await page.waitForURL('**/admin/kyc');
            await new Promise(r => setTimeout(r, 1000));

            logChunk = getNewLogEntries(offset);
            emails = extractEmailsFromLog(logChunk);
            results.test4 = {
                emailCount: emails.length,
                to: emails[0]?.to,
                subject: emails[0]?.subject,
                sampleBody: emails[0]?.cleanBody,
                raw: emails[0]?.raw,
                passed: emails.length === 1 && emails[0]?.subject.includes('Your Kaazilink Provider Verification is Approved')
            };
            console.log(`Sent count: ${emails.length}`);
            console.log(`To: ${results.test4.to}`);
            console.log(`Subject: ${results.test4.subject}\n`);
        }

        // =====================================================================
        // TEST 5: Admin rejects KYC with reason -> Provider notified with reason
        // =====================================================================
        console.log('--- TEST 5: Admin rejects KYC with reason -> provider notified with reason ---');
        // Ensure another candidate has a pending submission
        execSync('php artisan tinker --execute="\\App\\Models\\User::where(\'id\', 14)->update([\'kyc_status\'=>\'pending\']); \\App\\Models\\KycSubmission::create([\'user_id\'=>14, \'id_front_path\'=>\'dummy.jpg\', \'id_back_path\'=>\'dummy.jpg\', \'kra_pin_path\'=>\'dummy.jpg\', \'status\'=>\'pending\']);"');

        await page.goto(`${BASE_URL}/admin/kyc`);
        const pendingRow = page.locator('tr:has-text("Pending")').first();
        if (await pendingRow.count() > 0) {
            await pendingRow.locator('a:has-text("Review")').click();
            await page.waitForURL('**/admin/kyc/*');
            await page.waitForSelector('button:has-text("Reject KYC")');

            const rejectionReasonText = 'Your National ID copy is blurry and the expiration date cannot be verified.';
            await page.fill('textarea[wire\\:model="rejectionReason"]', rejectionReasonText);
            offset = getLogLength();
            await page.click('button:has-text("Reject KYC")');
            await page.waitForURL('**/admin/kyc');
            await new Promise(r => setTimeout(r, 1000));

            logChunk = getNewLogEntries(offset);
            emails = extractEmailsFromLog(logChunk);
            results.test5 = {
                emailCount: emails.length,
                to: emails[0]?.to,
                subject: emails[0]?.subject,
                sampleBody: emails[0]?.cleanBody,
                raw: emails[0]?.raw,
                hasReason: emails[0]?.raw.includes(rejectionReasonText),
                passed: emails.length === 1 && emails[0]?.subject.includes('Important Update: Your Kaazilink Verification Status') && emails[0]?.raw.includes(rejectionReasonText)
            };
            console.log(`Sent count: ${emails.length}`);
            console.log(`To: ${results.test5.to}`);
            console.log(`Subject: ${results.test5.subject}`);
            console.log(`Includes rejection reason: ${results.test5.hasReason}\n`);
        }

    } catch (err) {
        console.error('Error during verification:', err);
    } finally {
        await browser.close();
        fs.writeFileSync(path.resolve(__dirname, 'e2e_results.json'), JSON.stringify(results, null, 2));
        console.log('=== VERIFICATION RUN COMPLETED ===');
    }
}

run();
