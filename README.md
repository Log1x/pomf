# Pomf Stand-alone API for PHP

Here lives a simple, modernized, stand-alone implementation of the Pomf API commonly seen in screenshot tools such as [ShareX](https://github.com/ShareX/ShareX) and [Katana](https://github.com/bluegill/katana).

The purpose of this project is for those who want to self-host their screenshots without a frontend as seen in the original [Pomf](https://github.com/pomf/pomf-php) project.

### Features

- Clean, modernized code-base with PSR-2.
- Uses [Bulletproof](https://github.com/samayo/bulletproof) to securely (and properly) handle image uploads.
- Configurable token to prevent outside uploading.
- Customizable screenshot filenames with the ability to set a slug and timestamp (e.g. `Screenshot_2019-03-02_13-12-57.png`) or as a randomized string using the [Hashids](https://github.com/ivanakimov/hashids.php) library.

### Installation

```bash
$ composer create-project log1x/pomf screenshots
```

### Usage

- Set configuration in `config.php`.
- Upload the `pomf` folder contents to your server.
- Configure your Pomf App with your URL  (e.g. `https://example.com/screenshots/upload.php?token=secret`)

### Testing

For testing purposes, you can use `curl`:

```bash
curl -i -X POST -F 'file=test@path/to/test.jpg' 'https://example.com/screenshots/upload.php?token=secret'
```

or `httpie`:

```bash
http --form 'https://example.com/screenshots/upload.php?token=secret' 'test@path/to/test.jpg'
```
