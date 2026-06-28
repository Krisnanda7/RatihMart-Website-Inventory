import { expect, type Page } from "@playwright/test";

export async function loginAsAdmin(page: Page): Promise<void> {
    const email = process.env.TEST_EMAIL ?? "ratih@toko.com";
    const password = process.env.TEST_PASSWORD ?? "password";

    await page.goto("/login");
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.getByRole("button", { name: /masuk/i }).click();

    await expect(page.getByRole("heading", { name: /selamat datang/i })).toBeVisible();
}
