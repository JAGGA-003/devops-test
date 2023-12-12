from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
import unittest


class WebAppTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        # Set up Chrome options for headless mode
        chrome_options = Options()
        chrome_options.add_argument("--headless")

        # Specify the path to your ChromeDriver executable
        chrome_driver_path = "C:\\browserdrivers\\chromedriver.exe"

        # Initialize WebDriver instance and store it as a class attribute
        cls.driver = webdriver.Chrome(service=Service(chrome_driver_path), options=chrome_options)

        # Note: Ensure that the URL includes the protocol (http/https)
        cls.base_url = "http://localhost/Web%20App"

    @classmethod
    def tearDownClass(cls):
        # Close the browser after all tests are done
        cls.driver.quit()

    def setUp(self):
        # Additional setup tasks for each test case (if any)
        pass

    
    def test_login_successful(self):
        # Perform login with valid credentials
        self.driver.get(self.base_url + "/signin")
        username_input = self.driver.find_element_by_name("username")
        password_input = self.driver.find_element_by_name("password")
        submit_button = self.driver.find_element_by_css_selector("input[type='submit']")

        username_input.send_keys("admin")
        password_input.send_keys("admin")
        submit_button.click()

        # Verify redirection to the dashboard
        self.assertEqual(self.driver.current_url, "http://192.168.87.102:5000/")

    def test_login_failed(self):
        self.driver.get(self.base_url + "/signin")        # Perform login with invalid credentials
        username_input = self.driver.find_element_by_name("username")
        password_input = self.driver.find_element_by_name("password")
        submit_button = self.driver.find_element_by_css_selector("input[type='submit']")

        username_input.send_keys("invalid_username")
        password_input.send_keys("invalid_password")
        submit_button.click()

        # Check if the error message is displayed
        error_message = self.driver.find_element_by_css_selector("p[style='color: red;']").text
        self.assertEqual(error_message, "Invalid username or password. Please try again.")

    def test_empty_login_fields(self):
        self.driver.get(self.base_url + "/signin")  # Navigate to the login page
        submit_button = self.driver.find_element_by_css_selector("input[type='submit']")
        submit_button.click()

        # Check for the presence of the browser's native validation messages
        username_input = self.driver.find_element_by_name("username")
        password_input = self.driver.find_element_by_name("password")

        # Check for the 'required' attribute in the username and password inputs
        self.assertTrue(username_input.get_attribute("required"))
        self.assertTrue(password_input.get_attribute("required"))


    def test_successful_add_to_cart(self):
        self.driver.get(self.base_url + "/books/add/2")
        # Add a product to the cart
        product_name_input = self.driver.find_element_by_name("product_name")
        quantity_input = self.driver.find_element_by_name("quantity")
        add_to_cart_button = self.driver.find_element_by_css_selector("input[id='addToCart']")

        product_name_input.send_keys("HELLO_BOK")  # Replace with an actual product name
        quantity_input.send_keys("2")  # Replace with the desired quantity
        add_to_cart_button.click()

        # Check if the success message is displayed
        success_message = self.driver.find_element_by_css_selector("p[style='color:lightgreen']").text
        self.assertEqual(success_message, "book added.")

    def test_remove_from_cart(self):
        self.driver.get(self.base_url + "/remove/2")
        # Remove a product from the cart
        remove_button = self.driver.find_element_by_css_selector("input[value='Remove']")
        remove_button.click()

        # Check if the success message is displayed
        success_message = self.driver.find_element_by_css_selector("p[style='color: lightcoral;']").text
        self.assertEqual(success_message, "book removed from library.")


if __name__ == "__main__":
    unittest.main()
