package migrations

import (
	"fmt"

	models "example.com/golang-restfulapip/models"
	"gorm.io/gorm"
)

func Migrate(db *gorm.DB) error {
	// AutoMigrate will create the table if it does not exist
	err := db.AutoMigrate(&models.Lotterys{})
	if err != nil {
		return err
	}

	fmt.Println("Migration Successful")
	return nil
}
