import { test, expect } from "@playwright/test";
import dotenv from "dotenv";
import { loginAsAdmin } from "../helpers/auth";

dotenv.config();

test.describe("Fitur Laporan Penjualan", () => {
    test.beforeEach(async ({ page }) => {
        await loginAsAdmin(page);
    });

    test("Melihat laporan penjualan dengan filter tanggal", async ({ page }) => {
        await page.goto("/laporan/penjualan");

        await page.locator("select[name='periode']").selectOption("bulanan");
        await page.locator('input[name="bulan"]').fill("2026-06");
        await page.getByRole("button", { name: /Tampilkan/i }).click();

        await expect(page).toHaveURL(/laporan\/penjualan/);
        await expect(page.getByRole("heading", { name: /Laporan Penjualan/i })).toBeVisible();
        await expect(page.getByText(/Total Pendapatan/i)).toBeVisible();
    });
});
