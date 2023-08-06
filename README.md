![Code Coverage Badge](./plugin/.coverage/badge.svg)

# WebP Conversion Bundle

The WebP Conversion Bundle is a Symfony bundle designed to facilitate the automatic conversion of JPEG, BMP, PNG, and
GIF images to WebP format. By converting images to WebP, you can significantly reduce file sizes and improve website
rendering speed. This bundle is compatible with PHP 8.1 and 8.2, as well as Symfony 6.1, 6.2, and 6.3

## Configuration

The WebPConversionBundle allows you to configure various settings to customize the behavior of the image conversion
process. To do this, follow these steps:

1. **Install the bundle**: If you haven't already installed the **WebPConversionBundle**, add it to your Symfony project
   by running the following command:

    ```bash
    composer require codebuds/webp-conversion-bundle
    ```

2. **Override default configuration**: To customize the bundle's configuration, create a new file named
   **webp_conversion.yaml** inside the **config/packages** directory of your Symfony project (if it doesn't exist,
   create it).

3. **Configure the parameters**: In the **webp_conversion.yaml** file, you can set the desired configuration options.
   For
   example, to change the default quality and upload path, use the following syntax:

    ```yaml
    # config/packages/webp_conversion.yaml
    web_p_conversion:
    quality: 90
    upload_path: '/custom/upload/directory'
   ```
    - **quality**: The quality parameter sets the quality for the generated WebP images. By default, it is set to 80.
      You can adjust it to a higher or lower value as per your requirements.
    - **upload_path**: The upload_path parameter defines the directory where the WebP images will be created. By
      default, it is set to **/public/images**. You can change it to any other directory that suits your project
      structure.

## Command

This bundle provides a command, **codebuds:webp:convert**, that simplifies the process of converting images to WebP
format. The command accepts a directory as an argument, and all images (JPEG, PNG, GIF, and BMP) within that directory
will be converted to WebP. The command offers several optional parameters to customize the conversion:

- **--create**: Use this flag to generate the WebP images. If not provided, the directories will be checked, but the
  final
  images will not be created.
- **--quality**: Set the quality level for the WebP images (80 by default).
- **--force**: Use this flag to recreate existing WebP images. By default, existing WebP images will not be overwritten.
- **--suffix**: Add a suffix to the names of the created WebP images.

### Example:

```shell script
php bin/console codebuds:webp:convert --create --quality=90 --suffix=_q90 public/images
```

If the *public/images* directory contains an image named *test.jpg*, after executing the command, it will also contain
*test_q90.webp*.

## Twig extension

The bundle includes a Twig extension that generates WebP images and returns the path to them. This makes it easy to
create **<picture>** elements to optimize website rendering speed.

### Example

```html
<!-- Traditional approach -->
<img src="/public/images/test.jpg">

<!-- Modern approach using WebP -->
<picture>
    <source srcset="{{ '/images/test.jpg' | cb_webp }}" type="image/webp">
    <source srcset="/images/test.jpg" type="image/jpeg">
    <img src="/images/test.jpg">
</picture>

```

The Twig extension also supports VichUploaderBundle assets and LiipImagineBundle filters:

```html

<picture>
    <source srcset="{{ vich_uploader_asset(asset, 'imageFile') | cb_webp | set_webp_extension | imagine_filter(filter) }}"
            type="image/webp">
    <source srcset="{{ vich_uploader_asset(asset, 'imageFile') | imagine_filter(filter) }}">
    <img src="{{ vich_uploader_asset(asset, 'imageFile') | imagine_filter(filter) }}">
</picture>
```
