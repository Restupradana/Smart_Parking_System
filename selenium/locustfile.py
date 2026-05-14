from locust import HttpUser, task, between

class SmartTrashBinUser(HttpUser):
    wait_time = between(1, 5)  # jeda antar request (detik)

    @task(1)
    def login_page(self):
        self.client.get("/login")

    @task(2)
    def dashboard_page(self):
        self.client.get("/dashboard")
