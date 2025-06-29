# MediaFlow

A next-level, premium, responsive, and visually stunning PHP media browser and downloader. Effortlessly view, play, and download videos and music from your local server, or add new media by pasting a URL. Built with Tailwind CSS, Video.js, and modern UI/UX best practices.

---

## 🚀 Features

- **Beautiful, Responsive UI**: Modern glassmorphism, gradients, and dark mode support
- **Media Library**: Instantly browse, play, and download videos and music
- **Automatic Thumbnails**: Video thumbnails generated on the fly
- **Video.js Player**: Advanced controls, fullscreen, and PiP support
- **Audio Visualizer**: Animated waveform for music files
- **URL Downloader**: Paste a direct link to download new media
- **Category Tabs**: Filter by All, Videos, or Music
- **Grid/List Views**: Switch between grid and list layouts
- **Dark/Light Mode**: Toggle and remember your preference
- **Mobile Friendly**: Fully responsive for all devices
- **Notifications**: Toasts for user feedback

---

## 🖥️ Demo

![MediaFlow Screenshot](https://github.com/user-attachments/assets/c2efaf5d-fae0-4575-867c-28bf04b337a4)


---

## 📦 Installation

1. **Clone or Download** this repository to your server directory (e.g. `htdocs/media-downloader`)
2. **Ensure PHP 7.4+** is installed and running
3. **Install dependencies** (if any, e.g. via Composer)
4. **Set folder permissions** for `media/` and its subfolders
5. **Open** `http://localhost/media-downloader/` in your browser

---

## 🛠️ Usage

- **Browse**: Instantly see all your videos and music
- **Play**: Click any video or music to play in a beautiful player
- **Download**: Use the download button to save files locally
- **Add Media**: Paste a direct video/music/image URL and click Download
- **Switch Modes**: Use the sun/moon icon to toggle dark/light mode

---

## 📁 Project Structure

```
media-downloader/
├── api/
│   ├── process-url.php
│   └── thumbnail.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── includes/
│   ├── config.php
│   ├── functions.php
│   ├── MediaHandler.php
│   └── DownloadProcessor.php
├── media/
│   ├── videos/
│   └── music/
├── index.php
└── README.md
```

---

## ✨ Technologies Used

- [PHP 7.4+](https://www.php.net/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Video.js](https://videojs.com/)
- [Font Awesome](https://fontawesome.com/)
- [AOS (Animate On Scroll)](https://michalsnik.github.io/aos/)

---

## 💡 Customization

- **Change Colors/Branding**: Edit Tailwind config in `index.php`
- **Add More Media Types**: Update `MediaHandler.php` to support more extensions
- **Improve Thumbnails**: Enhance `api/thumbnail.php` for better video previews

---

## 🤝 Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

## 🙏 Credits

- Inspired by modern SaaS dashboards and premium media apps
- Icons by [Font Awesome](https://fontawesome.com/)
- Animations by [AOS](https://michalsnik.github.io/aos/)

---

## 📬 Contact

For support or feedback, open an issue or contact [enoshrodrigo930@gmail.com](mailto:enoshrodrigo930@gmail.com)
