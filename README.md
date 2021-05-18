# Really Rich Results - JSON-LD Structured Data (Google Rich Results) for WordPress

Search engines are putting more weight on structured data than ever before. By leveraging JSON-LD schema markup as part of your SEO strategy, a whole world of opportunities arise within Google's Rich Results.

Really Rich Results works with your WordPress site's existing content to quickly and accurately generate JSON-LD schema markup with minimal effort required. In addition to generating markup for the primary content on the page, Really Rich Results also detects any additional child elements that are being displayed, such as related content, products, page context, and more.

In addition to a large library of standard patterns that are automatically recognized, Really Rich Results offers limitless customization to fit your unique needs. Whether you're customizing your structured data from within the GUI or by providing your own custom code, Really Rich Results has you covered.

RRR makes structured data for WordPress really rich, and really easy.

## Building

The instructions below are for building from the GitHub repository. If you'd like to try out a pre-packaged version, see the [releases](https://github.com/pagely/really-rich-results/releases).

### Requirements
- Yarn
- Composer

### Compiling for Development

1. Clone the plugin.
2. Run a `composer install`.
3. Run `yarn install`.
4. Run `yarn start` to build the development bundle.
4. Activate.

### Compiling for Release

If you just want to download the latest release, see [releases](https://github.com/pagely/really-rich-results/releases).

1. Clone the plugin.
2. Run `yarn install`
3. Run `yarn build:dist`

## Extending

TODO

## How it works

Quick rundown of how it works:

1. Main functionality exists inside `src/Main.php`.
2. First it hooks into the `wp` action to check for the primary content.
3. Next, it hooks into the `the_content` hook to collect any posts being displayed. Those collected posts are detected, used to create a data source, and added to `Main::$found_posts`.
4. Data sources are then used to create schema objects. They can be either auto-detected or defined manually by passing a schema object and the data source through `Schema::build_schema()`
5. Finally, they're output through a `wp_footer` action. By this time, we should have all the posts we need to build any schema.

General locations for things are as follows:

* `really-rich-results.php`: Just loads up the autoloader and inits `src/Main.php`
* `src/Common.php`: Other stuff. Mostly helper functions.
* `src/Main.php`: Main functionality. Basically a controller. Collects queried posts, hands them off to wherever they need to go, stores objects, and outputs.
* `src/Admin/`: Houses any WordPress admin tasks, such as settings pages.
* `src/Schema/`: Transforms data sources into structured data schema objects. Files are named according to the schema type and inherit their parent schema. Follows the schema.org spec.
* `src/Data_Sources/`: Various data sources. Helps translate schema properties from different types of content.
* `src/Content_Types/`: Handles various content types that might need to handle a data source or group of data sources differently than normal. For example, archive pages that contain an ItemList of Article schema objects inside another main CollectionPage schema object.
* `src/Factories/`: Factories for generating schema and content type objects.
* `src/Routes/`: Contains REST API routes.
* `assets`: Various JS, SCSS, and image assets.
* `tests`: Codeception tests.

## Contributing

Pull requests or issue reports are always welcome. Please be sure to run the PHPCS ruleset at `phpcs.xml` before submitting a pull request.