USE [subastaBNB]
GO
ALTER TABLE [mdl_product] ALTER COLUMN [pro_description] VARCHAR(150)
GO
ALTER TABLE [mdl_users] ALTER COLUMN [mus_address] VARCHAR(150)
GO
ALTER TABLE [mdl_users] ALTER COLUMN [mus_photo] VARCHAR(100)
GO
ALTER TABLE [sys_language] ALTER COLUMN [lan_language] VARCHAR(100)
GO
ALTER TABLE [sys_users] ALTER COLUMN [usr_address] VARCHAR(100)
GO
ALTER TABLE [mdl_banners] ALTER COLUMN [ban_content] VARCHAR(100)
GO
ALTER TABLE [mdl_contents_languages] ALTER COLUMN [col_content] VARCHAR(100)
GO
ALTER TABLE [mdl_contents_languages] ALTER COLUMN [col_metatitle] VARCHAR(100)
GO
ALTER TABLE [mdl_contents_languages] ALTER COLUMN [col_metakeyword] VARCHAR(100)
GO
ALTER TABLE [mdl_contents_languages] ALTER COLUMN [col_media] VARCHAR(100)
GO
ALTER TABLE [mdl_incoterm] ALTER COLUMN [inc_lugar_entrega] VARCHAR(150)
GO