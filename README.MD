# Dynamic Forms in staticly cached pages for Statamic

Want static full/half measure caching in statamic?
But also still want to have forms in those static pages?
DynamoInStatamo is here to help.

### Installing

Copy the `DynamicToken` into your `site/addons` folder.

### Configuration

- disable CSRF verification by adding `/!/DynamicToken` to the `csrf_exclude` array in `site/settings/system.yaml`. Don't worry we check that the referrer is comming from your `APP_URL`, but this still carries its own risks.
- add a `{{ dynamic_token }}` to your layout file just before `</body>` tag.
- add an `APP_URL=<your_site_url>` to your `.env` file.
e.g.:`APP_URL=site.com`
local development: `APP_URL=localhost`

### How does this work?

First off, if `javascript` is disabled this won't work. We will add this functionality later. On page load it will get a fresh csrf token and update all your forms with it. It will automatically update the token every 15 minutes.

##### There are two parts:
###### 1st:
The `{{ dynamic_token }}` inserts a bit of javascript into your static html file, which allows it to pull a new `csrf` token and update all `<form>` tags which have an `<input name="_token">`, this applies to all `{{ form:create }}` tags, or any form if you add `<input name="_token">` to your normal forms.
###### 2st:
A simple controller, which checks the refferer and if the refferer matches your `APP_URL` it returns a fresh `csrf` token.

### Gotchas
If you've changed your api denominator, you will need to modify `$route = '/!/DynamicToken/refresh';` in the `DynamicToken/DynamicTokenTags.php` file. The default statamic denominator is `/!/`;

### Upcoming
- Settings: set refresh time span, currently 15 minutes
- Automatic pickup of your api denominator
- Server only handling of forms, without the `{{ dynamic_token }}` tag
