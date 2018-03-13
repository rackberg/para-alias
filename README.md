# Para-Alias Plugin

[![Build Status](https://travis-ci.org/rackberg/para-alias.svg?branch=master)](https://travis-ci.org/rackberg/para-alias)
[![Dependency Status](https://dependencyci.com/github/rackberg/para-alias/badge)](https://dependencyci.com/github/rackberg/para-alias)
[![Coverage Status](https://coveralls.io/repos/github/rackberg/para-alias/badge.svg?branch=master)](https://coveralls.io/github/rackberg/para-alias?branch=master)
[![Current Version](https://img.shields.io/badge/release-1.0.0-0e5487.svg)](https://github.com/rackberg/para-alias/releases)

A plugin for the para console application to be able to configure aliases and to use them in the shell.

## How to use it?
This plugin extends the `para` console application.

After installing this plugin you are able to use shell command aliases that you've manually defined in the para.yml file. 

### Prerequisites

If you don't have installed para, please take a look in the [Para README.md](https://github.com/rackberg/para) in order to install it. 

### Installing the plugin
To install the `para` plugin simply execute the following commands:
```
# Change into the directory where you installed para.
cd <para-install-path>

# Use composer to install the para-sync plugin
composer require lrackwitz/para-alias
```
If everything worked, you can now use the extended functionality of this plugin.

## Contributing
Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on the code of conduct, and the process for creating issues or submitting pull requests.

## Versioning
This project uses [SemVer](https://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/rackberg/para/tags).

Have a look at the [CHANGELOG.md](CHANGELOG.md).

## Authors
* **Lars Rosenberg** - *Initial work* - [Para](https://github.com/rackberg/para-alias) 

## License
This project is licensed under the GPL-3.0-or-later License - see the [LICENSE.md](LICENSE.md) file for details.
