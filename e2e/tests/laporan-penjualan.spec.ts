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
        await expect(page).toHaveURL(/.*dashboard|.*8000\/?$/);
    });
    test("Melihat laporan penjualan dengan filter tanggal", async ({
        page,
    }) => {
        const url_laporan_penjualan = process.env.LAPORAN_PENJUALAN_API_KEY!;
        await page.goto(url_laporan_penjualan);
        await page.waitForTimeout(1000);

        // Mengisi filter tanggal di bulanan
        await page.locator("select").first().selectOption("Bulanan");
        await page.waitForTimeout(1000);

        // Klik tombol "Tampilkan Laporan"
        await page.getByRole("button", { name: /Tampilkan/i }).click();
        await page.waitForTimeout(1000);

        // Assert bahwa kita berada di halaman laporan penjualan
        await expect(page).toHaveURL(/.*laporan/);

        // Assert bahwa elemen yang menunjukkan laporan penjualan muncul
        await expect(page.locator("text=Total Pendapatan")).toBeVisible({
            timeout: 10000,
        });
    });
});
