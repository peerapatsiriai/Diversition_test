package models

import (
	"gorm.io/gorm"
)

// Define the Collections struct
type Lotterys struct {
	gorm.Model
	Prize1   string `json:"prize1" orm:"size(128)" binding:"required"`
	Prize2_1 string `json:"prize2" orm:"size(64)" binding:"required"`
	Prize2_2 string `json:"prize3" orm:"size(64)" binding:"required"`
	Prize2_3 string `json:"prize4" orm:"size(64)" binding:"required"`
}
