import { test, expect } from "@playwright/test";
import dotenv from "dotenv";

dotenv.config();

test.describe("Login Test", () => {
    test("Login berhasil dengan kredensial valid", async ({ page }) => {
        const url = process.env.LOGIN_API_KEY!;

        await page.goto(url);
        await page.fill('input[name="email"]', "ratih@gmail.com");
        await page.waitForTimeout(1000);
        await page.fill('input[name="password"]', "ratih123");
        await page.waitForTimeout(1000);
        await page.click('button[type="submit"]');
    });
});
