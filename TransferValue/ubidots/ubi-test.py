from ubidots import ApiClient
import random
import time

# Create an "API" object
api = ApiClient("569f9980ce8645bad6b1074aaca5b031a26de7d6")

# Create a "Variable" object
test_variable = api.get_variable("5689b21076254235161ada87")

# Here is where you usually put the code to capture the data, either through your GPIO pins or as a calculation. We'll simply put a random value here:
for index in range(10):
  test_value = random.randint(1,100)

  # Write the value to your variable in Ubidots
  test_variable.save_value({'value':test_value})
  time.sleep(1)
