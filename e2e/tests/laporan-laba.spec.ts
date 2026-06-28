import { test, expect } from "@playwright/test";
import dotenv from "dotenv";
import { loginAsAdmin } from "../helpers/auth";

dotenv.config();

test.describe("Fitur Laporan Laba", () => {
    test.beforeEach(async ({ page }) => {
        await loginAsAdmin(page);
    });

    test("Melihat laporan laba dengan filter tanggal", async ({ page }) => {
        await page.goto("/laporan/laba-rugi");

        await page.locator('input[name="bulan"]').fill("2026-06");
        await page.getByRole("button", { name: /Tampilkan/i }).click();

        await expect(page).toHaveURL(/laporan\/laba-rugi/);
        await expect(page.getByRole("heading", { name: /Laporan Laba Rugi/i })).toBeVisible();
        await expect(page.getByText(/Laba Kotor/i)).toBeVisible();
    });
});
