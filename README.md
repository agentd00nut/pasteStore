# pasteStore

Easily send files, including images and most media types, to pastebin.

## Setup
Get your developer key from the [api pages](https://pastebin.com/api#1) and save it into the **devKey** file.

If you want to make private posts do the same but for your user key and save it into the **userKey** file.

## Use

Passing data in from a pipe sets a random name and the contents to STDIN.
`cat myImage.jpg | pasteStore.php`

Passing data in from a pipe with an arg sets the paste name to the arg and the contents to STDIN.
`cat myImage.webm | pasteStore.php thisIsMyCoolPaste`

Passing just an arg treats the argument as the path to a file and pulls its contents.
`pasteStore.php myImage.mp4`

Manually edit the code to change if its uploading as private or if its going to expire.

Better yet, write some argument handling and make a pull request!
