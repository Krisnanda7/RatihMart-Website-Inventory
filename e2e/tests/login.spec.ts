import { test, expect } from "@playwright/test";
import dotenv from "dotenv";
import { loginAsAdmin } from "../helpers/auth";

dotenv.config();

test.describe("Login Test", () => {
    test("Login berhasil dengan kredensial valid", async ({ page }) => {
        await loginAsAdmin(page);

        await expect(page).toHaveURL(/\/(dashboard)?$/);
        await expect(page.getByRole("heading", { name: /selamat datang/i })).toBeVisible();
    });
});
