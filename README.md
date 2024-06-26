# ClickGuard

ClickGuard is a PocketMine-MP plugin designed to detect and handle players using cheats such as Kill Aura or Auto Clicker. It monitors player interactions and kicks or bans players who exceed a specified click rate, ensuring fair gameplay on your Minecraft: Bedrock Edition server.

## Features

- Detects high click rates indicative of cheating
- Kicks players exceeding click rate threshold
- Bans players after repeated offenses
- Tracks violations across player sessions

## Installation

1. Download the latest release of ClickGuard.
2. Place the `ClickGuard` directory in the `plugins` folder of your PocketMine-MP server.
3. Start or restart your server.

## Configuration

Edit the `config.yml` file to adjust the click rate threshold and maximum violations:

```yaml
clicks-per-second: 20
max-violations: 3
```

## Usage

ClickGuard works out of the box. It automatically monitors player interactions and handles violations as follows:

- Players exceeding the configured clicks per second are kicked.
- After the configured number of violations, players are banned by IP.

## Commands

No commands are provided by ClickGuard.

## Permissions

No specific permissions are required for ClickGuard to operate.

## Contributing

Contributions are welcome! Please submit pull requests or open issues on GitHub.

## License

This project is licensed under the MIT License.

## Credits

Developed by ImmoDevs(Unreall).

## Support

For support and inquiries, please open an issue on the [GitHub repository](https://github.com/ImmoDevs/ClickGuard/issues).

