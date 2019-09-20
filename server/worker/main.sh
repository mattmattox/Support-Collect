#!/bin/bash

URL="localhost"

#while true
#do

TOKEN="$(curl -s -X POST -H "Content-Type: application/json" -d '{"email":"rancher@labs", "password":"rancher@labs"}' http://"$URL"/api/login.php | jq .jwt  | tr -d '"')"
echo "Token: $TOKEN"

environment_ids="$(curl -s -X GET http://"$URL"/api/environment/list.php -H "Accept: application/json" -H "Content-Type:application/json" --data-binary '{"jwt": "'"$TOKEN"'"}' | jq '.[]' | jq .id | tr -d '"')"
for environment_id in $environment_ids
do
        echo "Working on environment: $environment_id"
        OUTPUT="$(curl -s -X GET http://"$URL"/api/environment/read_one.php?id="$environment_id" -H "Accept: application/json" -H "Content-Type:application/json" --data-binary '{"jwt": "'"$TOKEN"'"}' | jq .)"
        environment_name="$(echo $OUTPUT | jq .name | tr -d '"')"
        environment_endpoint="$(echo $OUTPUT | jq .endpoint | tr -d '"')"
        environment_accesskey="$(echo $OUTPUT | jq .accesskey | tr -d '"')"
        environment_secretkey="$(echo $OUTPUT | jq .secretkey | tr -d '"')"
        echo "environment_name: $environment_name"
        echo "environment_endpoint:     $environment_endpoint"
        echo "environment_accesskey:    $environment_accesskey"
        echo "environment_secretkey:    $environment_secretkey"

        echo "Checking ping..."
        health="Unknown"
        OUTPUT="$(curl -s -k https://"$environment_endpoint"/ping)"
        if [ "$OUTPUT" == 'pong' ]
        then
            if curl -k -u "${environment_accesskey}:${environment_secretkey}" -X POST -H 'Accept: application/json' -H 'Content-Type: application/json' https://"$environment_endpoint"/v3/ > /dev/null
            then
              health="Healthy"
            fi
        else
            health="Unhealthy"
        fi

        echo "Updating health status..."
        curl -s -X GET http://"$URL"/api/environment/healthcheckupdate -H "Accept: application/json" -H "Content-Type:application/json" --data-binary \
        '{"jwt": "'"$TOKEN"'", "id": "'"$environment_id"'"}'
done

#done
