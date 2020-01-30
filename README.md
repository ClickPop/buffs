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

### Install Laravel Valet

Laravel's valet is amazing, it makes everything sooooooo easy. Unfortunately it only works on macOS. Thankfully there's a windows clone, but it's a big pain in the @$$ to set up. Sorry! :trombone:

***Below you'll find instructions for setting it up on both windows and mac.***

#### Install Valet for Windows

Install the windows clone of [Laravel's Valet](https://laravel.com/docs/6.x/valet). It can be found [here](https://github.com/cretueusebiu/valet-windows). To install it run:
`composer global require cretueusebiu/valet-windows`.

Once complete, launch your command prompt as an Administrator and run the command `valet install && valet domain test` to finish the setup. If it can't run valet, you may need to add the composer global /bin folder to your PATH variable. It should be located at `C:\Users\<username>\AppData\Roaming\Composer\vendor\bin` (be sure to replace <username> with your Windows username. Once it's complete, close the administrator version of the command prompt.
    
Run `valet link && valet secure && valet links` from within the Laravel project directory. Once it's complete you should see a table displaying that the site is currently running at something like: `https://buffs.test`.

#### Install Valet for macOS

The official [laravel/valet](https://laravel.com/docs/6.x/valet) package only works on macOS. To install it run:
`composer global require laravel/valet`

Once complete, run the command `valet install && valet domain test` to finish the setup.
    
Run `valet link && valet secure && valet links` from within the Laravel project directory. Once it's complete you should see a table displaying that the site is currently running at something like: `https://buffs.test`.

### Create your MySQL Table

Connect to your instance of MySQL with your tool of choice (i.e. MySQL Workbench, Sequel Pro, etc). Once connected, create a database named `buffs_dev` or similar, and be sure to note which username/password has access to it (probably root if you're doing this quickly). **It's important to note, laravel prefers you to have a password set for MySQL.**

> **A Note About MySQL**
> If you want an easy way to manage MySQL, feel free to install a tool like MAMP/XAMP/WampServer2 and use it to manage MySQL. Keep in mind, you must either disable Apache/NGINX in those tools, or ensure they're not using port 80(HTTP)/443(HTTPS). Valet utilizes standard ports, and will cause problems if another service creates a conflict.

### Update .env File

Download the latest copy of the .env file from the Google Share (or request one if you're new to the project). Be sure to set the correct database settings, URL/domain settings, etc. A sample .env file will be included for your convenience to use as a template, but things like OAUTH/Twitch API keys will not be included.

### Install Dependcies

Run the following commands to install & build any dependencies:
- `composer install` (Installs PHP & Laravel based dependencies)
- **IF YOU CREATED YOUR OWN .ENV FILE:** `php artisan key:generate` (Generates a new base64 app key, and adds it to your .env file)
- `php artisan migrate:fresh --seed` (Setup the database tables & seed the initial data)
- `npm install` (Installs Node dependcies like bootstrap, jquery, etc)
- `npm run dev` (Compiles the most recent JS/SCSS)

### Test Run

Check it out by visiting the link listed in `valet links` in your browser. Probably something like. `https://buffs.dev`. If everything is running/built correctly you should be in business!



