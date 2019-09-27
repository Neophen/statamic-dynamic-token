# Dynamic Forms in static pages for Statamic

Want static caching in statamic? And still want to have dynamic forms? for e.g.

![dynamic form in static page](https://github.com/Neophen/statamic-dynamic-token/blob/master/public/dynamic_in_static.png)

Just add a `{{ dynamic_token }}` to your layout file just before `</body>` tag.
then add an `APP_URL=site.com` to your `.env` file

### Installing
Copy the `DynamicToken` into your `site/addons` folder.
### How does this work?
First off, if `javascript` is disabled this won't work. We will add this functionality later. On page load it will get a fresh csrf token and update all your forms with it. It will automatically update the token every 15 minutes.
##### There are two parts:
###### 1st:
The `{{ dynamic_token }}` inserts a bit of javascript into your static html file, which allows it to pull a new `csrf` token and update all `<form>` tags which have an `<input name="_token">`, this applies to all `{{ form:create }}` tags, or any form if you add `<input name="_token">` to your normal forms.
###### 2st:
A simple controller, note you might need to exclude `/!/DynamicToken` from csrf setting like
Disable CSRF verification by adding `/!/DynamicToken` to the `csrf_exclude` array in `site/settings/system.yaml`. Don't worry we check that the referrer is comming from your `APP_URL`.

### Gotchas
If you've changed your api denominator, you will need to modify `$route = '/!/DynamicToken/refresh';` in the `DynamicToken/DynamicTokenTags.php` file. The default statamic denominator is `/!/`;
