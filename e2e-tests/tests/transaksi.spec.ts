import { test, expect } from "@playwright/test";
import dotenv from "dotenv";

dotenv.config();

test.describe("Fitur Transaksi Penjualan", () => {
  test.beforeEach(async ({ page }) => {
    const url_login = process.env.USER_API_KEY!;

    await page.goto(url_login);
    await page.fill('input[name="email"]', "ratih@gmail.com");
    await page.waitForTimeout(1000);
    await page.fill('input[name="password"]', "ratih123");
    await page.waitForTimeout(1000);
    await page.click('button[type="submit"]');
    await page.waitForTimeout(1000);
    await expect(page).toHaveURL(/.*dashboard|.*8000\/?$/);
  });

  test("Berhasil membuat transaksi sampai simpan", async ({ page }) => {
    const url_transaksi = process.env.TRANSAKSI_API_KEY!;
    await page.goto(url_transaksi);

    // --- SISI KANAN: Detail Pelanggan ---
    await page.getByPlaceholder(/Warung Bu Sari/i).fill("Umum");

    // --- SISI KIRI: Tambah Barang ---
    const searchInput = page.getByPlaceholder(/Ketik nama atau kode barang/i);
    await searchInput.fill("Minyak Goreng");
    await page.waitForTimeout(1000);

    // KLIK pada hasil yang muncul di dropdown (Autocomplete)
    // Berdasarkan gambar, teksnya adalah "Minyak Goreng Bimoli 2L"
    await page.getByText("Minyak Goreng Bimoli 2L").first().click();
    await page.waitForTimeout(1000);

    // --- INPUT QTY (Di dalam tabel) ---
    // Mencari input angka pertama yang muncul di tabel Item Transaksi
    const qtyInput = page.locator('td input[type="number"]').first();
    await qtyInput.fill("1");
    await page.waitForTimeout(1000);

    // --- PEMBAYARAN ---
    // Di gambar, input Uang Bayar berada di bawah Total Belanja
    // Kita gunakan locator label atau urutan spinbutton
    const uangBayar = page.locator('input[type="number"]').last();
    await uangBayar.fill("50000");
    await page.waitForTimeout(1000);

    // --- SIMPAN ---
    // Klik tombol biru di pojok kanan bawah
    await page.getByRole("button", { name: /Simpan Transaksi/i }).click();
    await page.waitForTimeout(1000);

    // --- ASSERTION ---
    await expect(page).toHaveURL(/.*transaksi/);
    await expect(page.getByText(/berhasil/i)).toBeVisible();
  });
});
