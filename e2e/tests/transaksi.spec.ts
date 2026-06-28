import { test, expect } from "@playwright/test";
import dotenv from "dotenv";
import { loginAsAdmin } from "../helpers/auth";

dotenv.config();

test.describe("Fitur Transaksi Penjualan", () => {
    test.beforeEach(async ({ page }) => {
        await loginAsAdmin(page);
    });

    test("Berhasil membuat transaksi sampai simpan", async ({ page }) => {
        await page.goto("/transaksi/create");

        await page.getByPlaceholder(/Warung Bu Sari/i).fill("Umum");

        const searchInput = page.getByPlaceholder(/Ketik nama atau kode barang/i);
        await searchInput.fill("Minyak Goreng");
        await page.getByText("Minyak Goreng Bimoli 2L").first().click();

        await page.locator('input[name$="[qty]"]').first().fill("1");
        await page.locator('input[name="total_bayar"]').fill("50000");

        await page.getByRole("button", { name: /Simpan Transaksi/i }).click();

        await expect(page).toHaveURL(/transaksi/);
        await expect(page.getByText(/berhasil/i)).toBeVisible();
    });
});
