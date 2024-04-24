# ZioMark's VORP Web Panel 🚀

This web panel provides a live view of your characters table from your VORP (RedM) server database. It allows you to monitor and manage character information in real-time.

# V2.1 UPDATE (24/04/2024)
### Preview
https://streamable.com/mdjf66

### Changelog
- Added SteamAuth system (will be used in the future for some exciting features!)
- The directory has been refactored by placing the pages in the "pages" folder and the backend PHP files in the "backend" folder.
- Added a Navbar (menu)
- Added login page for steamauth login
- Table restyle (tailwind classes)
- More

## Introduction

The VORP Web Panel is designed to offer a user-friendly interface for administrators to access character data directly from the database. With this panel, you can:

- View detailed information about each character.
- Manage character details such as name, age, job, money, and gold.
- Quickly access character information for player support or administrative tasks.

## Preview
![Login Page](https://i.imgur.com/ezqCaBv.jpeg)

![Homepage](https://i.imgur.com/AgVpBbO.png)

![Characters](https://i.imgur.com/3DXrqj0.png)

![Items](https://i.imgur.com/JwMkGal.png)


## Features

- Live view of character data: The panel reads the characters table live from the VORP server database, ensuring that you always have up-to-date information.
- Detailed character information: Each character entry includes details such as identifier, steam name, character identifier, group, Discord ID, and more.
- Interactive modal: Clicking on the "Details" button opens an interactive modal displaying additional information about the selected character.
- Responsive design: The web panel is built using Tailwind CSS, ensuring a responsive and visually appealing layout across devices.

## Getting Started

To deploy the VORP Web Panel, follow these steps:

1. Clone this repository to your web server.
2. Configure environment variables: Set up your database connection details in the `.env` file.
3. Move the .env file to another directory, a directory that isn't accessible thru APACHE (so not in the htdocs)
4. Install **Composer** on your machine [Composer](https://getcomposer.org/download/)
5. Go to the webpanel directory and open a CMD > write : `composer update` to make sure every dependecy and package is installed
6. Go to **steamauth** > SteamConfig.php > and fill the line **2 3 4 5** with your correct details.
7. Launch the web panel: Access the web panel through your web server to start managing character data.

## Dependencies

- PHP: The server-side scripting language used for dynamic web content.
- Tailwind CSS: A utility-first CSS framework for building responsive web designs.
- Font Awesome: A popular icon set and toolkit.

## License

This project is licensed under the MIT License - see the [LICENSE](https://github.com/ZioMark-s-HUB/zm_WebPanel/blob/main/LICENSE) file for details.
