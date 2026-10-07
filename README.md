# halftoberfest.com

The website for Halftoberfest, commemorating the legendary Flint River flood of 1986.

## What's here

- `index.html` – the whole site, including **Strike of '86**, a game where you play the catfish trying to bite Adam's toe
- `leaderboard.php` – saves and returns the game's top scores (needs a host that runs PHP)
- `catfish2.jpg`, `Crest_Clear.png` – site images
- favicons and `site.webmanifest` – browser and phone icons
- `info.php` – shows the server's PHP setup; handy for checking the host, but best not left on the live site

## Leaderboard

Scores are saved on the server in `scores.txt`, one per line: `INITIALS,POINTS,DATE`.
The file is created automatically the first time someone saves a score, as long as the web server can write to the site folder.
To reset the leaderboard, empty or delete `scores.txt` on the server.

`scores.txt` is listed in `.gitignore`, so it is never committed and uploading the site again won't wipe the live scores.
