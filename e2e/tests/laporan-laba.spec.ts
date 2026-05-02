import { test, expect } from "@playwright/test";
import dotenv from "dotenv";

dotenv.config();

test.describe("Fitur Laporan Laba", () => {
    test.beforeEach(async ({ page }) => {
        const url_login = process.env.LOGIN_API_KEY!;
        await page.goto(url_login);
        await page.fill('input[name="email"]', "ratih@gmail.com");
        await page.waitForTimeout(1000);
        await page.fill('input[name="password"]', "ratih123");
        await page.waitForTimeout(1000);
        await page.click('button[type="submit"]');
        await page.waitForTimeout(1000);
        await expect(page).toHaveURL(/.*dashboard|.*8000\/?$/);
    });

    test("Melihat laporan laba dengan filter tanggal", async ({ page }) => {
        const url_laporan_laba = process.env.LAPORAN_LABA_API_KEY!;
        await page.goto(url_laporan_laba);
        await page.waitForTimeout(1000);

        // Mengisi filter tanggal di bulanan
        await page.locator("input[type='month']").fill("2026-05");
        await page.waitForTimeout(1000);

        //klik tombol "Tampilkan Laporan"
        await page.getByRole("button", { name: /Tampilkan/i }).click();
        await page.waitForTimeout(1000);

        // Assert bahwa kita berada di halaman laporan laba
        await expect(page).toHaveURL(/.*laporan/);
    });
});
