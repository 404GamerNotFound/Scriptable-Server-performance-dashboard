# Server Monitoring Widget for Scriptable

This repository contains a Scriptable widget and accompanying server-side PHP scripts for real-time monitoring of server resources like CPU, RAM, disk usage, network traffic, and system information. The widget fetches data from your server via a PHP API and displays it on your iOS device.

## Features

- **Real-Time Monitoring:** Tracks CPU load, RAM and disk usage, network traffic, and displays system information.
- **Visual Representation:** Uses progress bars in the Scriptable widget for an easy understanding of resource usage.
- **Secure API Communication:** Implements basic API key authentication for secure data fetching.

## Getting Started

### Prerequisites

- A server running PHP (for the server-side scripts).
- An iOS device with the Scriptable app installed.

### Installation

1. **Clone the Repository**

   Clone this repository to your server and your local machine where you'll edit the Scriptable script.

   ```sh
   git clone https://github.com/404GamerNotFound/Scriptable-Server-performance-dashboard
   ```
2. **Server-Side Setup**

   - Upload the PHP scripts to your server. These scripts will provide the data for the widget.
   - Ensure that PHP is configured properly on your server.
   - Secure your PHP API by setting a strong API key in the script.

3. **Scriptable Widget Setup**

   - Copy the JavaScript code for the Scriptable widget.
   - Open the Scriptable app on your iOS device.
   - Create a new script and paste the copied JavaScript code.
   - Modify the script to include the URL of your server where the PHP scripts are hosted and the API key.

### Configuration

- **API Key:** Set a secure API key in the server-side PHP script and the Scriptable widget script.
- **Server URL:** Ensure that the Scriptable widget script points to the correct URL of your server.

## Usage

Run the Scriptable widget on your iOS device to view real-time server statistics. The widget will display the current status of various server resources using a visually intuitive interface.

## Security

- **Do not expose sensitive server information.**
- **Regularly update your API key and use HTTPS to secure data transmission.**

## Contributing

Contributions, issues, and feature requests are welcome! Feel free to check [issues page](https://github.com/404GamerNotFound/Scriptable-Server-performance-dashboard/issues).

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Contact

Tony Brüser - Tony@Brüser.com

Project Link: [https://github.com/404GamerNotFound/Scriptable-Server-performance-dashboard](https://github.com/404GamerNotFound/Scriptable-Server-performance-dashboard)

