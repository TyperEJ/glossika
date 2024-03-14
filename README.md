# Glossika 專案任務

## 簡介
Glossika 專案任務

## 啟動PHP、MySQL和Redis
```cmd
docker compose up -d
```

## 執行部署與啟動
```cmd
docker compose exec app sh deploy.sh
```

## API Document
```cmd
http://localhost/request-docs/
```

## 執行自動化測試
###### 請務必先執行部署與啟動後才能執行自動化測試
```cmd
docker compose exec app php artisan test
```