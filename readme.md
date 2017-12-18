## WPT Rebrandly URL Shortener
WPT Rebrandly URL Shortener is an extension for the popular WordPress plugin, WP to Twitter [https://wordpress.org/plugins/wp-to-twitter/] by Joe Dolson [https://www.joedolson.com]. The extension adds Rebrandly.com URL shortening, with an option to use a custom domain, if you have one registered with Rebrandly.

### Installation
1. Upload `wpt-rebrandly-url-shortener.php` to your `plugins` directory
2. Go to your site's plugin page (i.e., Dashboard > Plugins > Installed Plugins)
3. Activate WPT Brandly URL Shortener

*Requires an active installation of the WP to Twitter plugin.*

### Set Up Extension
1. Go to the WP to Twitter settings (i.e., Dashboard > WP to Twitter > Basic Settings)
2. In the `Choose a URL Shortener` dropdown at the top, select `Rebrandly`
3. Save settings
4. Go to https://www.rebrandly.com/api-settings and create a new API key for your account
5. Go to URL Shortener settings (i.e., Dashboard > WP to Twitter > URL Shortener)
6. Enter the API Key you created in step 4
7. Save Settings

### Use a Custom Domain by Default
1. If you have a custom short domain setup with Rebrandly, follow the instructions in the URL Shortener settings to find and set the Domain ID of the domain this extension should use.
2. Save settings.

### Using the Extension
As of the time version 1.0 of this extension was released (Dec 2017), WP to Twitter produces a short URL from the selected shortening service when using the `#url#` template tag in generated and custom tweets. If Rebrandly is selected as your shortener service, and set up correctly with at least a valid API Key, then you should begin seeing short links in your tweets for new posts.
