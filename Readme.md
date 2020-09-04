# EZ Platform Admin UI Entrypoint Components
This package adds a few new admin UI components for including assets from the [entrypoints.json](https://symfony.com/doc/current/frontend/encore/simple-example.html#encore-entrypointsjson-simple-description) file that can be used as part of the [Ez platform admin UI component system](https://doc.ezplatform.com/en/latest/extending/custom_components/). This is useful for when you want to use encore generated assets in the CMS.
## Compatibility:
Currently these components have only been tested on eZ platform 3.0+

## Usage:

This package adds 3 new admin ui components:
 * `Bluetel\EzEntrypointComponentBundle\Component\EntrypointComponent`
 * `Bluetel\EzEntrypointComponentBundle\Component\EntrypointCSSComponent`
 * `Bluetel\EzEntrypointComponentBundle\Component\EntrypointScriptComponent`

To use these components add / a new entrypoint into your `webpack.config.js` file.

In our example we will be adding a new entrypoint for extending the `create_form` component. Adding a new entry into our webpack config exposes the webpack assets in our entrypoints.json file.

```js
// Webpack.config.js
Encore
    .addEntry('admin_ui_create_form_extend', './src/Resources/public/assets/js/main.js')
```

When running webpack your entrypoints.json will now look something like this depending on the assets you are exporting from new entrypoint

```js
//public/assets/build/entrypoints.json
{
    "admin_ui_create_form_extend": {
      "js": [
        "/assets/build/runtime.js",
        "/assets/build/admin_ui_create_form_extend.js"
      ],
      "css": [
        "/assets/build/admin_ui_create_form_extend.css"
      ]
    }
  },
  ...
}
```

Once we have a new entrypoint defined you can add a extend definition like 

```yml
services:
    app.cms.extend.content_create_form:
      parent: Bluetel\EzEntrypointComponentBundle\Component\EntrypointCSSComponent
      autowire: true
      autoconfigure: false
      arguments:
          $entryName: "admin_ui_create_form_extend"
      tags:
          - { name: ezplatform.admin_ui.component, group: content-create-form-after }
```

Adding this will automatically add include statements for all files listed under the `CSS` and `JS` keys in the `entrypoints.json` file generated by webpack.

## Detailed Reference

### Including all assets for an entry:
The `EntrypointComponent` component includes all assets for a given entrypoint.json
file. The available arguments to pass to it are:

* `$entryName` REQUIRED: The name of the entry that should be included
* `$entrypointName`: The entrypoint collection to use

### Including only JS files from a given entry:
The `EntrypointScriptComponent` allows you to include all the scripts given in the `js` key under the `entrypoint.json`. All attributes are applied globally across included scripts for the entrypoint.

The available arguments to pass to it are:
* `$entryName` REQUIRED: The name of the entry that should be included
* `$entrypointName`: The entrypoint collection to use
* `$async`: If the scripts should be included as async or not. See [attr-async](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script#attr-async) for more info on this
* `$defer`: If the script should be load deferred. See [`attr-deferred`](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script#attr-defer) for more info.
* `$crossorigin`: If the asset should be loaded cross origin or not. See [attr-cross-origin](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script#attr-crossorigin) for more info.
* `$integrity`: Metadata for verifying the integrity of the given assets. See [attr-integrity](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script#attr-integrity) for more info.

### Including only CSS files from a given entry 
The `EntrypointCSSComponent` allows you to include all the stylesheets given in the `css` key under the `entrypoint.json`. All attributes are applied globally across included scripts for the entrypoint.


The available arguments to pass to it are:
* `$entryName` REQUIRED: The name of the entry that should be included
* `$entrypointName`: The entrypoint collection to use
* `$crossorigin`: If the asset should be loaded cross origin or not. See [attr-cross-origin](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/link#attr-crossorigin) for more info.
* `$integrity`: Metadata for verifying the integrity of the given assets. See [attr-integrity](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/link#attr-integrity) for more info.
