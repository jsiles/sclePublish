/****** Object:  Table [dbo].[mdl_listIp]    Script Date: 18-Jun-17 9:47:09 PM ******/
DROP TABLE [dbo].[mdl_listIp]
GO
/****** Object:  Table [dbo].[mdl_listIp]    Script Date: 18-Jun-17 9:47:09 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[mdl_listIp](
	[lis_uid] [int] IDENTITY(1,1) NOT NULL,
	[lis_ip] [varchar](20) NOT NULL,
	[lis_delete] [int] NOT NULL CONSTRAINT [DF_mdl_listIp_lis_delete]  DEFAULT ((0)),
 CONSTRAINT [PK_mdl_listIp] PRIMARY KEY CLUSTERED 
(
	[lis_uid] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO

SET IDENTITY_INSERT [dbo].[mdl_listIp] ON 

GO
INSERT [dbo].[mdl_listIp] ([lis_uid], [lis_ip], [lis_delete]) VALUES (1, N'127.0.0', 0)
GO
INSERT [dbo].[mdl_listIp] ([lis_uid], [lis_ip], [lis_delete]) VALUES (2, N'::', 0)
GO
SET IDENTITY_INSERT [dbo].[mdl_listIp] OFF
GO

/****** Object:  Table [dbo].[mdl_lock]    Script Date: 20-Jun-17 8:17:18 AM ******/
DROP TABLE [dbo].[mdl_lock]
GO
/****** Object:  Table [dbo].[mdl_lock]    Script Date: 20-Jun-17 8:17:18 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[mdl_lock](
	[loc_uid] [int] IDENTITY(1,1) NOT NULL,
	[loc_ipV4] [varchar](20) NOT NULL,
	[loc_datetime] [datetime] NOT NULL,
	[loc_retry] [int] NOT NULL CONSTRAINT [DF_mdl_lock_loc_retry]  DEFAULT ((0)),
	[loc_estado] [int] NOT NULL CONSTRAINT [DF_mdl_lock_loc_estado]  DEFAULT ((0)),
 CONSTRAINT [PK_mdl_lock] PRIMARY KEY CLUSTERED 
(
	[loc_uid] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[mdl_version]    Script Date: 20-Jun-17 8:21:30 AM ******/
DROP TABLE [dbo].[mdl_version]
GO
/****** Object:  Table [dbo].[mdl_version]    Script Date: 20-Jun-17 8:21:30 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[mdl_version](
	[ver_uid] [int] NOT NULL
) ON [PRIMARY]

GO
INSERT [dbo].[mdl_version] ([ver_uid]) VALUES (6)
GO
