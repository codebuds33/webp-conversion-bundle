# WebP Conversion Command

This bundle is here to make it easy to create webP images in a Symfony project.

## Command

The bundle contains one command so far. `codebuds:webp:convert`.

This will allow you to pass a directory in which you want all the jpeg, png, gif and bmp images to be converted to webP.

It has multiple parameters :

- `--create` without this the directories will be checked, but the final images will not be created.
- `--quality` set the quality for the webp images (80 by default)
- `--force` recreate existing webP images (false by default)
- `--suffix` add a suffix to the created webp image names

Example:

```shell script
php bin/console codebuds:webp:convert --create --quality=90 --suffix=_q90 public/images
```

if the public/images contains image.jpeg, after the command it will contain image_q90.webp.