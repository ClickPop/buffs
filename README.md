## Setup

Let's take a few minutes to ensure your system is set up to run this Laravel web app. If you're already up and running, you should be good to go... unless you want a refresher course.

### All Platforms

You will need to make sure you have the following installed before you even think about moving forward:
- Git (obviously)
- PHP (We recommend 7.2+)
- Composer
- Node/NPM
- MySQL (5.7 or 8)

Once you are assured the above are installed, be sure you can access the following via your preferred shell/command prompt: `composer`, `npm`, `git`. If you can execute those commands, you're good for now.

### Install and Setup Homestead

* For Windows, I would use [this tutorial](https://medium.com/@eaimanshoshi/i-am-going-to-write-down-step-by-step-procedure-to-setup-homestead-for-laravel-5-2-17491a423aa "Installing Homestead on Windows 10") from Medium. - *I have just found this one a little easier than the laravel docs because Windows is so weird*

* For MacOS or Linux, I would use the [Laravel Docs](https://laravel.com/docs/5.8/homestead#installation-and-setup "macOS and Linux Installation").

### A quick note for those on Windows

You will need to add 3 separate entries to your hosts file. They are:

1. buffs.test
2. oauth.buffs.test
3. app.buffs.test

### A note about MySQL

Be sure to name your database something like **buffs_dev** and reflect that in your `homestead.yaml` file.

### Update .env File

Download the latest copy of the .env file from the Google Share (or request one if you're new to the project). Be sure to set the correct database settings, URL/domain settings, etc. A sample .env file will be included for your convenience to use as a template, but things like OAUTH/Twitch API keys will not be included.

### Install Dependcies

Run the following commands to install & build any dependencies:
- `composer install` (Installs PHP & Laravel based dependencies) *If on Windows, be sure to run this from within your Homestead VM via SSH* `ssh vagrant@127.0.0.1 -p 2222`
- `npm install` (Installs Node dependcies like bootstrap, jquery, etc)
- `npm run dev` (Compiles the most recent JS/SCSS)

### Test Run

Check it out by visiting the link listed in your `homestead.yaml` in your browser. Probably something like. `https://buffs.test`. If everything is running/built correctly you should be in business!
