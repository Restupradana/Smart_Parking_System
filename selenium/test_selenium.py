from selenium import webdriver
from selenium.webdriver.edge.service import Service
from selenium.webdriver.common.by import By
import time
from datetime import datetime

# ✅ Menggunakan driver Edge manual (sudah didownload)
service = Service("C:/webdriver/msedgedriver.exe")  # Ganti jika lokasi berbeda
driver = webdriver.Edge(service=service)

# URL Laravel
BASE_URL = "http://127.0.0.1:8000"  # Sesuaikan dengan URL project Laravel Anda

# Variabel untuk menyimpan hasil pengujian
results = []

def log_result(test_name, status, message):
    """Menyimpan hasil pengujian ke list"""
    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    results.append({
        "test": test_name,
        "status": status,
        "message": message,
        "time": now
    })
    print(f"[{test_name}] {status} - {message}")

# ========== TEST REGISTER ==========
def test_register():
    test_name = "REGISTER TEST"
    driver.get(f"{BASE_URL}/register")
    time.sleep(2)
    try:
        driver.find_element(By.NAME, "name").send_keys("Test User")
        driver.find_element(By.NAME, "email").send_keys("testuser@example.com")
        driver.find_element(By.NAME, "password").send_keys("password123")
        driver.find_element(By.NAME, "password_confirmation").send_keys("password123")
        driver.find_element(By.NAME, "wa_number").send_keys("81234567890")

        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(3)

        if "dashboard" in driver.current_url:
            log_result(test_name, "✅ Sukses", "Berhasil mendaftar & diarahkan ke dashboard")
            # ✅ Langsung logout setelah berhasil register
            test_logout(after_register=True)
        else:
            log_result(test_name, "❌ Gagal", "Tidak diarahkan ke dashboard")
    except Exception as e:
        log_result(test_name, "❌ Error", str(e))

# ========== TEST LOGIN ==========
def test_login():
    test_name = "LOGIN TEST"
    driver.get(f"{BASE_URL}/login")
    time.sleep(2)
    try:
        driver.find_element(By.NAME, "email").send_keys("testuser@example.com")
        driver.find_element(By.NAME, "password").send_keys("password123")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(3)
        if "dashboard" in driver.current_url:
            log_result(test_name, "✅ Sukses", "Berhasil login")
        else:
            log_result(test_name, "❌ Gagal", "Gagal masuk ke dashboard")
    except Exception as e:
        log_result(test_name, "❌ Error", str(e))

# ========== TEST LOGOUT ==========
def test_logout(after_register=False):
    test_name = "LOGOUT TEST (After Register)" if after_register else "LOGOUT TEST"
    try:
        # ✅ Klik ikon user dropdown terlebih dahulu
        driver.find_element(By.ID, "userDropdown").click()
        time.sleep(1)

        # ✅ Klik tombol logout di dalam form
        logout_button = driver.find_element(By.CSS_SELECTOR, "form[action*='logout'] button[type='submit']")
        logout_button.click()

        time.sleep(2)

        # ✅ Perbaikan logika pengecekan logout:
        # Cek apakah diarahkan ke halaman login ATAU ke halaman utama ("/")
        current_url = driver.current_url
        if "login" in current_url or current_url.rstrip("/") == BASE_URL.rstrip("/"):
            log_result(test_name, "✅ Sukses", f"Berhasil logout (URL sekarang: {current_url})")
        else:
            log_result(test_name, "❌ Gagal", f"Tidak diarahkan keluar (URL sekarang: {current_url})")
    except Exception as e:
        log_result(test_name, "❌ Error", str(e))



# ========== EKSEKUSI TEST ==========
test_register()
test_login()
test_logout()

driver.quit()

# ========== GENERATE LAPORAN ==========
html_report = """
<html>
<head>
    <title>Laporan Hasil Pengujian Selenium</title>
    <style>
        body {font-family: Arial; margin: 20px;}
        table {border-collapse: collapse; width: 100%;}
        th, td {border: 1px solid #ddd; padding: 8px;}
        th {background-color: #4CAF50; color: white;}
    </style>
</head>
<body>
    <h2>Laporan Hasil Pengujian Selenium - Smart Trash Bin</h2>
    <table>
        <tr><th>Waktu</th><th>Test</th><th>Status</th><th>Catatan</th></tr>
"""

txt_report = "=== Laporan Hasil Pengujian Selenium - Smart Trash Bin ===\n\n"

for r in results:
    html_report += f"<tr><td>{r['time']}</td><td>{r['test']}</td><td>{r['status']}</td><td>{r['message']}</td></tr>"
    txt_report += f"[{r['time']}] {r['test']} - {r['status']} : {r['message']}\n"

html_report += "</table></body></html>"

with open("laporan_selenium.html", "w", encoding="utf-8") as f:
    f.write(html_report)

with open("laporan_selenium.txt", "w", encoding="utf-8") as f:
    f.write(txt_report)

print("\n✅ Laporan pengujian telah dibuat: 'laporan_selenium.html' dan 'laporan_selenium.txt'")
