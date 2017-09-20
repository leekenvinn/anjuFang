CREATE TABLE `AspNetRoles` (
    `Id` varchar(127) NOT NULL,
    `ConcurrencyStamp` longtext,
    `Description` longtext,
    `Name` varchar(256),
    `NormalizedName` varchar(256),
    CONSTRAINT `PK_AspNetRoles` PRIMARY KEY (`Id`)
); 
CREATE TABLE `AspNetUsers` (
    `Id` varchar(127) NOT NULL,
    `AccessFailedCount` int NOT NULL,
    `ConcurrencyStamp` longtext,
    `Configuration` longtext,
    `Email` varchar(256),
    `EmailConfirmed` bit NOT NULL,
    `FullName` longtext,
    `IsEnabled` bit NOT NULL,
    `JobTitle` longtext,
    `LockoutEnabled` bit NOT NULL,
    `LockoutEnd` datetime(6),
    `NormalizedEmail` varchar(256),
    `NormalizedUserName` varchar(256),
    `PasswordHash` longtext,
    `PhoneNumber` longtext,
    `PhoneNumberConfirmed` bit NOT NULL,
    `SecurityStamp` longtext,
    `TwoFactorEnabled` bit NOT NULL,
    `UserName` varchar(256),
    CONSTRAINT `PK_AspNetUsers` PRIMARY KEY (`Id`)
);
CREATE TABLE `AppCustomers` (
    `Id` int NOT NULL AUTO_INCREMENT,
    `Address` longtext,
    `City` varchar(50),
    `DateCreated` datetime(6) NOT NULL,
    `DateModified` datetime(6) NOT NULL,
    `Email` varchar(100),
    `Gender` int NOT NULL,
    `Name` varchar(100) NOT NULL,
    `PhoneNumber` varchar(30),
    CONSTRAINT `PK_AppCustomers` PRIMARY KEY (`Id`)
);
CREATE TABLE `AppProductType` (
    `Id` int NOT NULL AUTO_INCREMENT,
    `DateCreated` datetime(6) NOT NULL,
    `DateModified` datetime(6) NOT NULL,
    `Description` varchar(500),
    `Icon` longtext,
    `Name` varchar(100) NOT NULL,
    CONSTRAINT `PK_AppProductType` PRIMARY KEY (`Id`)
);
CREATE TABLE `AspNetUserTokens` (
    `UserId` varchar(127) NOT NULL,
    `LoginProvider` varchar(127) NOT NULL,
    `Name` varchar(127) NOT NULL,
    `Value` longtext,
    CONSTRAINT `PK_AspNetUserTokens` PRIMARY KEY (`UserId`, `LoginProvider`, `Name`)
);
CREATE TABLE `OpenIddictApplications` (
    `Id` varchar(127) NOT NULL,
    `ClientId` varchar(127),
    `ClientSecret` longtext,
    `DisplayName` longtext,
    `LogoutRedirectUri` longtext,
    `RedirectUri` longtext,
    `Type` longtext,
    CONSTRAINT `PK_OpenIddictApplications` PRIMARY KEY (`Id`)
);
CREATE TABLE `OpenIddictScopes` (
    `Id` varchar(127) NOT NULL,
    `Description` longtext,
    CONSTRAINT `PK_OpenIddictScopes` PRIMARY KEY (`Id`)
);
CREATE TABLE `AspNetRoleClaims` (
    `Id` int NOT NULL AUTO_INCREMENT,
    `ClaimType` longtext,
    `ClaimValue` longtext,
    `RoleId` varchar(127) NOT NULL,
    CONSTRAINT `PK_AspNetRoleClaims` PRIMARY KEY (`Id`),
    CONSTRAINT `FK_AspNetRoleClaims_AspNetRoles_RoleId` FOREIGN KEY (`RoleId`) REFERENCES `AspNetRoles` (`Id`) ON DELETE CASCADE
);
CREATE TABLE `AspNetUserClaims` (
    `Id` int NOT NULL AUTO_INCREMENT,
    `ClaimType` longtext,
    `ClaimValue` longtext,
    `UserId` varchar(127) NOT NULL,
    CONSTRAINT `PK_AspNetUserClaims` PRIMARY KEY (`Id`),
    CONSTRAINT `FK_AspNetUserClaims_AspNetUsers_UserId` FOREIGN KEY (`UserId`) REFERENCES `AspNetUsers` (`Id`) ON DELETE CASCADE
);
CREATE TABLE `AspNetUserLogins` (
    `LoginProvider` varchar(127) NOT NULL,
    `ProviderKey` varchar(127) NOT NULL,
    `ProviderDisplayName` longtext,
    `UserId` varchar(127) NOT NULL,
    CONSTRAINT `PK_AspNetUserLogins` PRIMARY KEY (`LoginProvider`, `ProviderKey`),
    CONSTRAINT `FK_AspNetUserLogins_AspNetUsers_UserId` FOREIGN KEY (`UserId`) REFERENCES `AspNetUsers` (`Id`) ON DELETE CASCADE
);
CREATE TABLE `AspNetUserRoles` (
    `UserId` varchar(127) NOT NULL,
    `RoleId` varchar(127) NOT NULL,
    CONSTRAINT `PK_AspNetUserRoles` PRIMARY KEY (`UserId`, `RoleId`),
    CONSTRAINT `FK_AspNetUserRoles_AspNetRoles_RoleId` FOREIGN KEY (`RoleId`) REFERENCES `AspNetRoles` (`Id`) ON DELETE CASCADE,
    CONSTRAINT `FK_AspNetUserRoles_AspNetUsers_UserId` FOREIGN KEY (`UserId`) REFERENCES `AspNetUsers` (`Id`) ON DELETE CASCADE
);
CREATE TABLE `AppOrders` (
    `Id` int NOT NULL AUTO_INCREMENT,
    `CashierId` varchar(127),
    `Comments` varchar(500),
    `CustomerId` int NOT NULL,
    `DateCreated` datetime(6) NOT NULL,
    `DateModified` datetime(6) NOT NULL,
    `Discount` decimal(65, 30) NOT NULL,
    CONSTRAINT `PK_AppOrders` PRIMARY KEY (`Id`),
    CONSTRAINT `FK_AppOrders_AspNetUsers_CashierId` FOREIGN KEY (`CashierId`) REFERENCES `AspNetUsers` (`Id`) ON DELETE NO ACTION,
    CONSTRAINT `FK_AppOrders_AppCustomers_CustomerId` FOREIGN KEY (`CustomerId`) REFERENCES `AppCustomers` (`Id`) ON DELETE CASCADE
);
CREATE TABLE `AppProducts` (
    `Id` int NOT NULL AUTO_INCREMENT,
    `BuyingPrice` decimal(65, 30) NOT NULL,
    `DateCreated` datetime(6) NOT NULL,
    `DateModified` datetime(6) NOT NULL,
    `Description` varchar(500),
    `Icon` varchar(256),
    `IsActive` bit NOT NULL,
    `IsDiscontinued` bit NOT NULL,
    `Name` varchar(100) NOT NULL,
    `ParentId` int,
    `ProductTypeId` int NOT NULL,
    `SellingPrice` decimal(65, 30) NOT NULL,
    `UnitsInStock` int NOT NULL,
    CONSTRAINT `PK_AppProducts` PRIMARY KEY (`Id`),
    CONSTRAINT `FK_AppProducts_AppProducts_ParentId` FOREIGN KEY (`ParentId`) REFERENCES `AppProducts` (`Id`) ON DELETE NO ACTION,
    CONSTRAINT `FK_AppProducts_AppProductType_ProductTypeId` FOREIGN KEY (`ProductTypeId`) REFERENCES `AppProductType` (`Id`) ON DELETE CASCADE
);
CREATE TABLE `OpenIddictAuthorizations` (
    `Id` varchar(127) NOT NULL,
    `ApplicationId` varchar(127),
    `Scope` longtext,
    `Subject` longtext,
    CONSTRAINT `PK_OpenIddictAuthorizations` PRIMARY KEY (`Id`),
    CONSTRAINT `FK_OpenIddictAuthorizations_OpenIddictApplications_ApplicationId` FOREIGN KEY (`ApplicationId`) REFERENCES `OpenIddictApplications` (`Id`) ON DELETE NO ACTION
);
CREATE TABLE `AppOrderDetails` (
    `Id` int NOT NULL AUTO_INCREMENT,
    `Discount` decimal(65, 30) NOT NULL,
    `OrderId` int NOT NULL,
    `ProductId` int NOT NULL,
    `Quantity` int NOT NULL,
    `UnitPrice` decimal(65, 30) NOT NULL,
    CONSTRAINT `PK_AppOrderDetails` PRIMARY KEY (`Id`),
    CONSTRAINT `FK_AppOrderDetails_AppOrders_OrderId` FOREIGN KEY (`OrderId`) REFERENCES `AppOrders` (`Id`) ON DELETE CASCADE,
    CONSTRAINT `FK_AppOrderDetails_AppProducts_ProductId` FOREIGN KEY (`ProductId`) REFERENCES `AppProducts` (`Id`) ON DELETE CASCADE
);
CREATE TABLE `OpenIddictTokens` (
    `Id` varchar(127) NOT NULL,
    `ApplicationId` varchar(127),
    `AuthorizationId` varchar(127),
    `Subject` longtext,
    `Type` longtext,
    CONSTRAINT `PK_OpenIddictTokens` PRIMARY KEY (`Id`),
    CONSTRAINT `FK_OpenIddictTokens_OpenIddictApplications_ApplicationId` FOREIGN KEY (`ApplicationId`) REFERENCES `OpenIddictApplications` (`Id`) ON DELETE NO ACTION,
    CONSTRAINT `FK_OpenIddictTokens_OpenIddictAuthorizations_AuthorizationId` FOREIGN KEY (`AuthorizationId`) REFERENCES `OpenIddictAuthorizations` (`Id`) ON DELETE NO ACTION
);