=== MoveAdvisor Leads Form ===
Contributors: MoveAdvisor
Tags: my moving loads, moveadvisor, moving leads form, moving contact form, removal leads contact form
Requires at least: 4.3
Tested up to: 6.1.1
Requires PHP: PHP 5.4
License: General Public License v3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

The MoveAdvisor Leads Form is an affiliate moving lead generation plugin designed to help affiliates generate moving leads from their WordPress site easily and with a higher conversion rate. Check out the full set of features that make this plugin so efficient.

== Description ==
We have created this plugin to make it easy for our affiliates to generate quality moving leads. At the same time we wanted to provide the best conversion rate possible for our affiliate partners. This is why we've spent a lot of time creating the additional features of this plugin. 

**Please note** that this plugin requires a free API key for the MoveAdvisor lead system in order to capture leads.

**How does the plugin work**
Visitors visit a website where they see the form that is generated by the plugin. If a visitor is about to move, he or she will enter their move details and contact information and will submit the form. Based on the move information from the lead they may get one of the following:

- The user’s lead will be sent to MoveAdvisor and is sent to moving companies that will contact the user with more information regarding their move. The user will see a “Thank You” message that the lead is submitted and that he or she should expect their moving quote soon.

- For some US, Canadian and UK leads, the MoveAdvisor system may decide to call the user directly and connect the user with the moving company operator in real time. In those cases instead of a “Thank You” page, users will see “Wait, we are calling you now” page. Then the MoveAdvisor bot with Artificial Intelligence will call the user and will connect the user with a moving expert over the phone. The call is also considered a lead. Please note that this feature is still in beta.

**Currently this form consists of 4 different forms that are automatically displayed to the customer based on the location.** You do not need to select which form is displayed to who as the plugin does that automatically. Here are the different form types:

- **US Moving leads form**
For users that are moving within The US. The form is in Imperial units and is tailored to the default preferences of the US users. Users will be able to switch to international form if they are moving out of the USA.

- **Canadian Moving leads form**
For users that are moving within Canada. The form is in Metric units and is tailored to the default preferences of the Canadian users. Users will be able to switch to international form if they are moving out of Canada.

- **Australian Moving leads form**
For users that are moving within Australia. The form is in Metric units and is tailored to the default preferences of the users in Australia. Users will be able to switch to international form if they are moving out of Australia.

- **German Moving leads form**
For users that are moving within Germany. The form is in Metric units and is tailored to the default preferences of the users in Germany. Users will be able to switch to international form if they are moving out of Germany.

- **UK Moving leads form**
For users that are moving within The UK. The form is in Metric units and is tailored to the default preferences of the users in The United Kingdom. Users will be able to switch to international form if they are moving out of the UK.

- **International Moving leads form**
This form is for users that are moving between countries.

== Installation ==
Here is a **quick guide on how to install the plugin** to your WordPress site:

- Login as administrator to your WordPress site.
- From the Admin panel, see the left menu and click on **Plugins > Add New**.
- In the search box, search for MoveAdvisor Forms
- When you find the plugin in the search results, install it from there directly. Follow on screen instructions.
Please note: When the plugin is deleted all options set in the WordPress MoveAdvisor Form Admin panel are deleted, as well as the IP detection database. If you install the plugin again, the IP detection database installs again and options need to be set again. We have designed the plugin in this way to keep your WordPress installation clean from unnecessary code and as lightweight as possible.

**Setting up the plugin**
In order for the MoveAdvisor Form Affiliate plugin to submit leads, you need a valid API key provided by MoveAdvisor.com. The API key is free, but you need to [register here to be approved](https://moveadvisor.com/biz/affiliate#get-api-key-form). The API key will be issued to you or to your organization only and you should not share the API key with third parties. Based on the API key, your moving leads will be attributed to your account.

**Inserting a form into a page or post**
It is very easy to insert a new form to a page or a post within your WordPress site.

**Show automatic form type based on location detection (recommended)**
To have the form automatically show the right version of the form (US, United Kingdom, Canada, Australia, Germany or International) based on the user's location, just add this shortcode where you want it to appear:
**[mml_leadform]**

**Show a specific type of form without location detection**
In rare cases, you might want to show a specific type of form without taking into account the user's location, you can use the following shortcodes:

**United States version of the form:**
[mml_leadform country="us"]

**United Kingdom's version of the form:**
[mml_leadform country="uk"]

**Canadian version of the form:**
[mml_leadform country="ca"]

**Australian version of the form:**
[mml_leadform country="au"]

**German version of the form:**
[mml_leadform country="de"]

**International version of the form:**
[mml_leadform country="int"]

Here is some more information on [how to use shortcodes in WordPress](https://www.competethemes.com/blog/how-to-shortcodes-wordpress/).

This plugin consists of four different forms that are dynamically served based on the user's IP address. The form is responsive and is suitable for desktop and mobile pages. You can read more about it in the FAQs section below in this page.

**Inserting a form into the sidebar**
You may want to have a form appearing on every page of the WordPress site. Adding it to the sidebar as a widget will be the easiest option. Here is how to do it:

- Login as administrator to your WordPress site.
- From the Admin panel, see the left menu and click on **Appearance > Widgets**.
- There you will find the widget called **MoveAdvisor Lead Form**.
- Simply drag and drop it into your **Sidebar** section to the right for the form to appear in your sidebar.

**Inserting the form into a template file (for advanced users)**
To insert the form into a template file, [please find here the PHP code you need.](https://moveadvisor.com/biz/wp-lead-form#Inserting_the_form_into_a_template_file_for_advanced_users)

Note: Do not forget to add the php surrounding tags if pasting inside HTML.

This plugin consists of four different forms that are dynamically served based on the user's IP address. The form is responsive and is suitable for desktop and mobile pages. For more information, please visit the plugin's extensive documentation here: https://moveadvisor.com/biz/wp-lead-form

== Frequently Asked Questions ==
**What are the different types of forms in this plugin?**
Currently this form consists of 4 different forms that are automatically displayed to the customer based on the location. You do not need to select which form is displayed to who as the plugin does that automatically. Here are the different form types:

- **US Moving leads form**
This form is for users that are moving within The US. The form is in Imperial units and is tailored to the default preferences of the US users. Users will be able to switch to international form if they are moving out of the USA.

- **Canadian Moving leads form**
This form is for users that are moving within Canada. The form is in Metric units and is tailored to the default preferences of the Canadian users. Users will be able to which to international form if they are moving out of Canada.

- **UK Moving leads form**
This form is for users that are moving within The UK. The form is in Metric units and is tailored to the default preferences of the users in The United Kingdom. Users will be able to which to international form if they are moving out of the UK.

- **Australian Moving leads form**
For users that are moving within Australia. The form is in Metric units and is tailored to the default preferences of the users in Australia. Users will be able to switch to international form if they are moving out of Australia.

- **German Moving leads form**
For users that are moving within Germany. The form is in Metric units and is tailored to the default preferences of the users in Germany. Users will be able to switch to international form if they are moving out of Germany.

- **International Moving leads form**
This form is for users that are moving between countries.

**Which moving leads forms are displayed to the user?**
Based on IP detection, the user is delivered the most appropriate moving lead form. This ensures a much higher conversion rate and more captured moving leads. To read more about how to insert a form into your WordPress site, please read [the plugin documentation here](https://moveadvisor.com/biz/affiliate#get-api-key-form).

**How does the IP tracking happen?**
The IP database is automatically installed when you install this plugin. **The accuracy of the database is estimated to be 98%-99%.** The database is maintained and updated and we will be updating it with the future plugin updates.

**Are the moving lead forms responsive? Are they mobile friendly?**
Yes. The forms are responsive and they display well on mobile devices based on the screen resolution. We advise that you always check the Desktop and the Mobile design of the pages where you place the moving form before you update the design of your WordPress site.

**Can I change the button text?**
Yes. Go to the MoveAdvisor Form plugin page in your WordPress Admin panel. Under **Moving Leads** in section **Form Design** you can enter any text. Please note that this field does not support HTML or PHP tags and their attributes. Save the changes and refresh the page with the form.

**Can I change the default color scheme?**
Yes. The plugin comes with 3 color options you can choose from. Each option changes the color of the outline of all input fields, the background of the autosuggest as well as the dynamic information displayed inside some input fields after values are entered (for example the notification **About x days left**).

**How can I make a custom color scheme? (for advanced users)**
The easiest way to do it is to apply the color changes in your CSS StyleSheet in your theme. You need to know how to edit the HTML and CSS of your WordPress theme.

**Where can I see the leads that the forms generate?**
You can log into your account at MoveAdvisor to see all the leads that you have generated. [Please login from here](https://portal.moveadvisor.com/providers/).

**What is the payout for the leads?**
The payout for every lead is different and is determined by or real-time bidding algorithm. Please contact your affiliate manager for more information.

**How can I track the number of submitted moving leads?**
You can track the submitted leads by:

- Having a Google Analytics tracking code on your site and having checked the **Track Form** checkbox in the plugin's settings page.
- Logging into the MoveAdvisor system with your account. [Please login from here](https://portal.moveadvisor.com/providers/).

Please note that the numbers of the two tracking options may and will vary. This is because you will see only valid moving leads into your MoveAdvisor panel (all batches of duplicate leads as well as leads with errors will not be present into your MoveAdvisor account).

**What does the **Track Form** functionality do?**
If you have Google Analytics installed on your website it is easy to track how many people see and submit your moving leads forms.

Having Google Analytics set up and the **Track Form** functionality activated, you will start receiving events in your Google Analytics' admin panel. All your events are visible in **Behavior > Events > Overview**. 

The events are sent under a **category called MoveAdvisor Form**. This category has actions, which depend on the type of form sent. For US, Canada, UK, Australia, Germany and International forms the actions will be **us**, **ca**, **uk**, **au**, **de** and **int** respectively. The last piece of information of each event sent is the event label. For every form view and form submission the labels are Successfully-Sent and Successfully-Viewed respectively.

**For example, you will get the following events:**

- When a Canadian MoveAdvisor form is shown to a user's screen the event will be  MoveAdvisor Form > ca > Successfully-Viewed
- When an International form is submitted successfully to our system the event will be MoveAdvisor Form > int > Successfully-Sent

Please note: Google Analytics may track duplicate leads, so there is a chance that the number of submissions indicated in Google Analytics is different than the amount of moving leads you see in the MoveAdvisor Admin Panel.

**Can I use the same MoveAdvisor API key on multiple WordPress sites?**
Yes. Your API key is issued to you or your organization and you can use it with all the WordPress sites that you control.

**Can I use the plugin without a MoveAdvisor API key?**
No. The plugin works only with a valid API key. If you'd like to see how the form looks without having it send leads to our system, use this key for testing purposes: demokey

**I see no 'API key' warning on top of the form. How to remove it?**
The warning is visible only when logged into the WordPress system as an Administrator. Regular visitors will not see this warning message. To remove the warning, please add a valid MoveAdvisor API key or uninstall the plugin.

**How to get a MoveAdvisor API key?**
If you don't already have a MoveAdvisor API key, [please fill in an application here](https://moveadvisor.com/biz/affiliate#get-api-key-form). Once your application is approved, you will receive an account for the MoveAdvisor system as well as a valid API key. You then can [login here into the MoveAdvisor system](https://portal.moveadvisor.com/providers/) and monitor all received leads.

Do not display your MoveAdvisor API key publicly, since it is issued to you or to your organization only.

**How much does the MoveAdvisor API key cost?**
It's free, you do not pay for the API key.

**How can I test the form before I get an API key?**
To see how the form looks and behaves on your site please do the following.

- Login to your WordPress Administration panel.
- Go to the MoveAdvisor Leads Form settings page.
- Find the MoveAdvisor API Key field and insert the word: demokey
- Click **Save changes**
- Go to the page/post where you have placed your form and refresh it, you should be able to see the form.

The form will not submit leads but you can test if it works and you can see how it looks on your page.

**Do I need a Google API key for the form to work?**
No, but you will not be able to take advantage of all the extras and functionalities of the form.

**Why do I need a Google API key?**
Google API key activates functionality that provides the following extra information for the user:

- Displaying state and city names when ZIP or Postcode code is entered.
- Displaying distance between pickup and delivery locations.
- Showing type ahead for US, United Kingdom and International forms.
- Your conversion rate will increase if you have a Google API key and the forms will be more interactive.

**Does this Google API key affects form tracking?**
No. It is only for displaying additional useful information to the user while filling up the form.

**How can I get a Google API key? Is it free?**
The Google API key is free up to a certain amount of requests per month. Most of the users will be fine with the free Google API key. You can obtain a [free Google API key from here](https://developers.google.com/maps/documentation/javascript/get-api-key).

**How to get a Google API Key?**
The Google API key is free up to a certain amount of requests per month. Most of the users will be fine with the free Google API key. To obtain and use a Google API key, please do the following:

- Go to the Google API Console.
- Make sure the **Create a project** option is selected from the dropdown menu.
- Click **Continue** to enable the API.
- In the following Credentials page, make sure the **Google Places API Web Service** option is selected from the dropdown menu.
- Click **What credentials do I need?** to get to the API key.
- Copy your new API key and click Done.
- While logged into your website's admin panel, just go to the MoveAdvisor Leads Form settings page from the admin sidebar menu.
- Paste your new API key in the field of **MoveAdvisor API key**.
- Click Save Changes to activate your API key and unlock the extra features.

**Does the form work with multiple WordPress sites?**
Yes, but you should install and setup the plugin separately to every WordPress site.

**Why does it take so long to install?**
The first time (fresh) installation may take up to 20 seconds to download and activate the plugin. This is because the plugin comes with four different moving lead forms and an IP detection database. Please wait for the installation process to complete before closing the window. After installation, please do not forget to setup the plugin and to insert forms on your pages.

**Is this plugin slow?**
No. We believe that this plugin is very fast and will not impact the load time of your pages. We've kept the code as clean as possible. The IP detection runs very fast because the IP database is distributed with the plugin.

**If I deactivate the plugin, will I lose all my settings?**
No. You will lose your settings only if you delete the plugin from the Plugins page.

**What kind of technology is used within this plugin?**
This plugin has a lot of different types of technology inbuilt in the finished product. There are a total of 4 different web forms into the plugin that are served dynamically to the user based on their location. We also use a modified version of bootstrap typeahead to display the city suggestions in USA and Canada. Some functionalities call the Google API and display information through JavaScript. Most of the functionalities require a valid MoveAdvisor API key.

== Useful links ==
[Full plugin documentation and tutorials](https://moveadvisor.com/biz/wp-lead-form)

[Moving leads affiliate program description](https://moveadvisor.com/biz/affiliate)

[Registration form to be approved for an official MoveAdvisor API key](https://moveadvisor.com/biz/affiliate#get-api-key-form)

[MoveAdvisor affiliate administration panel login page /you will get your login info after having received an official API key/](https://portal.moveadvisor.com/providers/)

[MoveAdvisor contact page](https://moveadvisor.com/about)

== Screenshots ==
1. The US form as it will appear in a page or post.
2. Again the US form, but this time as a widget in a sidebar.
3. The International form as it will appear in a page or post.
4. This is the Canadian moving leads form in a page or post.
5. The UK moving leads form in a page or post.
6. The MoveAdvisor plugin page in the Wordpress administration panel.
7. The list of leads you've generated in the MoveAdvisor system.
8. The New Zealand moving leads form in a page or post.
9. The Australian moving leads form in a page or post.
10. The German moving leads form in a page or post.

== Changelog ==
This is the latest official version of the MoveAdvisor Leads Plugin v2
