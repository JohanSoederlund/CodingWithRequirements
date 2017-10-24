## Added Requirement specification
Editor: JohanSoederlund

## Original Use Cases and Test Cases can be found at:
[Use Cases](https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/UseCases.md)
[Test Cases](https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/TestCases.md)


# Extra-UC1 Login option stay hidden
## Main scenario
 1. Starts when a user wants to authenticate.
 2. System asks for username, password, loginoption stay hidden and if system should save the user credentials
 3. User provides username and password, and tics the box for loginoption stay hidden
 4. System authenticates the user and presents that authentication succeeded
 5. System sets visibility mode to hidden
 5. User shows up in the bottom of the page as hidden

## Alternate Scenarios
* E-1b. Loginoption stay hidden is not selected
   * 1. System sets visibility mode to visible
     2. User shows up in the bottom of the page as visible


# Extra-UC2 Render logged in users
## Preconditions
A user is authenticated. Ex. Extra-UC1
## Main scenario Render logged in users
 1. User is authenticated.
 2. System checks for 
 1. User can Not see a list of other logged in users in the bottom of the page 
 2. User shows up in the bottom of the page as hidden
 3. Username is Not visible to other users

## Preconditions for Alternate Scenarios
A user is authenticated. Ex. Extra-UC1 Alternate scenarios E-1b
## Alternate Scenarios
* E-2b. Loginoption stay hidden is not selected Loginoption stay hidden is not selected
   * 1. User can see a list of other logged in users in the bottom of the page 
     2. User shows up in the bottom of the page as visible
     3. Username is visible to other logged in users
        







## Test case Extra-UC1, Test login option stay hidden
Navigate to index.php, fill in and submit form. 

### Input:
 * Valid username
 * Valid password
 * Keep logged in is optional
 * Check Stay Hidden: <input type="checkbox" id="LoginView::Hidden" name="LoginView::Hidden">
 * submit.
 
### Output:
 * The text "Logged in", is shown.
 * A form-button for logout is shown
 * Todays date and time is shown in correct format.
 * Header with string "- Hidden -   User:"yourvalidusername"   - Hidden -"
 

## Test case Extra-UC2, Test option, NOT checked
Navigate to index.php, fill in and submit form. 

### Input:
 * Valid username
 * Valid password
 * Keep logged in is optional
 * DO Not check Stay Hidden.
 * submit.
 
### Output:
 * The text "Logged in", is shown.
 * A form-button for logout is shown
 * Todays date and time is shown in correct format.
 * Header with string "- Visible - User:"yourvalidusername" - Visible -"
 * Header with string "ONLINE NOW"
 * Unordered List with logged in users:     Nick:"yourvalidusername", Nick:"someoneelse"
 

## Test case Extra-UC3, logout and login with another user
Navigate to index.php, fill in and submit form. 

### Input:
 * Another Valid username
 * Another Valid password
 * Keep logged in is optional
 * DO Not check Stay Hidden.
 * submit.
 
### Output:
 * The text "Logged in", is shown.
 * A form-button for logout is shown
 * Todays date and time is shown in correct format.
 * Header with string "- Visible - User:"Another Valid username" - Visible -"
 * Header with string "ONLINE NOW"
 * Unordered List with logged in users:     Nick:"Another Valid username", Nick:"someoneelse"
 * This time your previous user is not showing up in online list. Missing:   Nick:"yourvalidusername"