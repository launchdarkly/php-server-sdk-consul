# Change log

All notable changes to the project will be documented in this file. This project adheres to [Semantic Versioning](http://semver.org).

## [3.2.0] - 2023-10-25
### Changed:
- Expanded SDK version support to v6

## [3.1.0] - 2022-12-28
### Changed:
- Relaxed the SDK version dependency constraint to allow this package to work with the upcoming v5.0.0 release of the LaunchDarkly PHP SDK.

## [3.0.0] - 2022-06-15
This release changes the integration to use the 5.x version of the Consul client (`friendsofphp/consul-php-sdk`).

## [2.0.0] - 2022-06-15
This release changes the integration to use the 4.x version of the Consul client (`sensiolabs/consul-php-sdk`), rather than the 2.x version.

It will not work with the 5.x version of the Consul client, because the package name and the namespace were changed in that release. We will release another major version of this integration for use with that version.

## [1.0.0] - 2021-08-06
Initial release, for use with version 4.x of the LaunchDarkly PHP SDK.

