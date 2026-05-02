import { test, expect } from "playwright/test";
import dotenv from "dotenv";
import { url } from "inspector/promises";

dotenv.config();

test.describe("Fitur Laporan Penjualan", () => {
    test.beforeEach(async ({ page }) => {
        const url_login = process.env.LOGIN_API_KEY!;
        await page.goto(url_login);
        await page.fill('input[name="email"]', "ratih@gmail.com");
        await page.waitForTimeout(1000);
        await page.fill('input[name="password"]', "ratih123");
        await page.waitForTimeout(1000);
        await page.click('button[type="submit"]');
        await page.waitForTimeout(1000);
        await expect(page).toHaveURL("http://127.0.1:8000/");
    });
});

test.describe("Berhasil mencetak laporan penjualan", () => {
    test("Mencetak laporan penjualan dengan filter tanggal", async ({
        page,
    }) => {
        const url_laporan_penjualan = process.env.LAPORAN_PENJUALAN_API_KEY!;
        await page.goto(url_laporan_penjualan);
        await page.waitForTimeout(1000);
        // Mengisi filter tanggal
        await page.locator('input[name="start_date"]').fill("2024-01-01");
        await page.waitForTimeout(1000);
        await page.locator('input[name="end_date"]').fill("2024-12-31");
        await page.waitForTimeout(1000);
        // Klik tombol "Cetak Laporan"
        await page.getByRole("button", { name: /Cetak Laporan/i }).click();
        await page.waitForTimeout(1000);
        // Assert bahwa laporan berhasil dicetak (misalnya, dengan memeriksa URL atau pesan sukses)
        await expect(page).toHaveURL(/.*laporan-penjualan\/cetak/);
        await expect(page.getByText(/Laporan Penjualan/i)).toBeVisible();
    });
});
