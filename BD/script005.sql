ALTER TABLE mdl_subasta_informe ADD sua_dateApr datetime null 
go
/****** Object:  Table [dbo].[mdl_notificacion_previa]    Script Date: 07/06/2017 10:38:30 a.m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[mdl_notificacion_previa](
	[nop_uid] [int] IDENTITY(1,1) NOT NULL,
	[nop_sub_uid] [int] NOT NULL,
	[nop_tiempo] [int] NOT NULL,
	[nop_datetime] [datetime] NOT NULL,
	[nop_estado] [int] NOT NULL,
 CONSTRAINT [PK_mdl_notificacion_previa] PRIMARY KEY CLUSTERED 
(
	[nop_uid] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
