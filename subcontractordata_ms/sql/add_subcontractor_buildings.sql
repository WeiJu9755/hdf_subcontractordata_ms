-- 下包商 1～10 對應的棟別多選欄位。
-- 儲存格式：items.caption 以逗號串接，例如「A棟,B棟」。
ALTER TABLE `CaseManagement`
  ADD COLUMN `subcontractor_buildings1` TEXT DEFAULT NULL COMMENT '下包商1棟別（多選）' AFTER `construction_floor1`,
  ADD COLUMN `subcontractor_buildings2` TEXT DEFAULT NULL COMMENT '下包商2棟別（多選）' AFTER `construction_floor2`,
  ADD COLUMN `subcontractor_buildings3` TEXT DEFAULT NULL COMMENT '下包商3棟別（多選）' AFTER `construction_floor3`,
  ADD COLUMN `subcontractor_buildings4` TEXT DEFAULT NULL COMMENT '下包商4棟別（多選）' AFTER `construction_floor4`,
  ADD COLUMN `subcontractor_buildings5` TEXT DEFAULT NULL COMMENT '下包商5棟別（多選）' AFTER `construction_floor5`,
  ADD COLUMN `subcontractor_buildings6` TEXT DEFAULT NULL COMMENT '下包商6棟別（多選）' AFTER `construction_floor6`,
  ADD COLUMN `subcontractor_buildings7` TEXT DEFAULT NULL COMMENT '下包商7棟別（多選）' AFTER `construction_floor7`,
  ADD COLUMN `subcontractor_buildings8` TEXT DEFAULT NULL COMMENT '下包商8棟別（多選）' AFTER `construction_floor8`,
  ADD COLUMN `subcontractor_buildings9` TEXT DEFAULT NULL COMMENT '下包商9棟別（多選）' AFTER `construction_floor9`,
  ADD COLUMN `subcontractor_buildings10` TEXT DEFAULT NULL COMMENT '下包商10棟別（多選）' AFTER `construction_floor10`;
