import { test, expect } from "@playwright/test";
import dotenv from "dotenv";

dotenv.config();

test.describe("Testing Barang (Login + CRUD)", () => {
  test.beforeEach(async ({ page }) => {
    const url_login = process.env.USER_API_KEY!;

    await page.goto(url_login);
    await page.waitForTimeout(1000);
    await page.fill('input[name="email"]', "ratih@gmail.com");
    await page.waitForTimeout(1000);
    await page.fill('input[name="password"]', "ratih123");
    await page.waitForTimeout(1000);
    await page.click('button[type="submit"]');
    await page.waitForTimeout(1000);

    // Gunakan regex yang mengizinkan '/' opsional di akhir atau cukup cek 'dashboard'
    await expect(page).toHaveURL(/.*(dashboard|8000\/?)$/);

    // Atau cara yang lebih robust: tunggu sampai elemen spesifik di dashboard muncul
    await expect(
      page.getByRole("heading", { name: /selamat datang/i }),
    ).toBeVisible();
  });

  test("Tambah barang berhasil", async ({ page }) => {
    const kodeBarang = `SBK-${Date.now()}`;
    const url_barang = process.env.BARANG_API_KEY!;

    await page.goto(url_barang);
    await page.waitForTimeout(1000);

    // Menggunakan locator yang lebih spesifik (Atribut name atau Placeholder)
    // Karena getByLabel sering gagal jika HTML tidak menggunakan tag <label> yang benar
    await page.locator('input[name="kode_barang"]').fill(kodeBarang);
    await page
      .locator('input[name="nama_barang"]')
      .fill("Minyak Goreng Bimoli 2L");
    await page.waitForTimeout(1000);

    // Untuk Kategori (berdasarkan snapshot terlihat seperti input biasa/combobox)
    await page.getByPlaceholder(/Sembako, Minuman, Snack/i).fill("Sembako");

    // Satuan
    await page.locator('select[name="satuan"]').selectOption("pcs");
    await page.waitForTimeout(1000);

    // Input angka (Spinbutton)
    await page.locator('input[name="harga_beli"]').fill("20000");
    await page.waitForTimeout(1000);
    await page.locator('input[name="harga_jual"]').fill("25000");
    await page.waitForTimeout(1000);
    await page.locator('input[name="stok"]').fill("10");
    await page.waitForTimeout(1000);
    await page.locator('input[name="stok_minimum"]').fill("5");
    await page.waitForTimeout(1000);

    // Deskripsi
    await page
      .locator('textarea[name="deskripsi"]')
      .fill("Barang sembako premium");
    await page.waitForTimeout(1000);

    // Klik Simpan
    await page.getByRole("button", { name: /simpan barang/i }).click();

    // Verifikasi Akhir
    await expect(page).toHaveURL(/.*barang/);
    await expect(page.getByText(/berhasil/i)).toBeVisible();
  });
});
