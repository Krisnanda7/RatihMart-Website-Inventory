import { test, expect } from "@playwright/test";
import dotenv from "dotenv";
import { loginAsAdmin } from "../helpers/auth";

dotenv.config();

test.describe("Testing Barang (Login + CRUD)", () => {
    test.beforeEach(async ({ page }) => {
        await loginAsAdmin(page);
    });

    test("Tambah barang berhasil", async ({ page }) => {
        const kodeBarang = `SBK-${Date.now()}`;

        await page.goto("/barang/create");
        await page.locator('input[name="kode_barang"]').fill(kodeBarang);
        await page.locator('input[name="nama_barang"]').fill("Minyak Goreng Bimoli 2L");
        await page.locator('input[name="kategori"]').fill("Sembako");
        await page.locator('select[name="satuan"]').selectOption("pcs");
        await page.locator('input[name="harga_beli"]').fill("20000");
        await page.locator('input[name="harga_jual"]').fill("25000");
        await page.locator('input[name="stok"]').fill("10");
        await page.locator('input[name="stok_minimum"]').fill("5");
        await page.locator('textarea[name="deskripsi"]').fill("Barang sembako premium");

        await page.getByRole("button", { name: /simpan barang/i }).click();

        await expect(page).toHaveURL(/barang/);
        await expect(page.getByText(/berhasil/i)).toBeVisible();
    });
});
