<?php

namespace App\Utils;

class Response
{

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public static function success(
        ?int $code,
        ?string $message,
        ?array $data = [],
        ?array $otherData = [],
        ?array $filter = []

    ): array {
        return [
            "status" => true,
            "code" => $code ?? 400,
            "data" => $data ?? [],
            "otherData" => $otherData ?? [],
            "filter" => $filter ?? [],
            "message" => $message ?? "Success...",
        ];
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public static function error(
        ?int $code,
        ?string $message,
        ?string $functionName,
        ?array $data = [],
        ?array $otherData = [],
        ?array $filter = []
    ): array {
        return [
            "status" => false,
            "code" => $code ?? 400,
            "data" => $data ?? [],
            "otherData" => $otherData ?? [],
            "filter" => $filter ?? [],
            "message" => $message ?? "Error...",
            "functionName" => $functionName ?? "",
        ];
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public static function response(
        ?int $code,
        ?string $title = "",
        ?string $message = "",
        ?string $messageError = "",
        ?string $otherMessage = "",
        ?string $functionName = "",
        ?array $data = [],
        ?array $otherData = [],
    ): array {
        return [
            "status" => $code == 200,
            "code" => $code ?? 400,
            "data" => $data ?? [],
            "otherData" => $otherData ?? [],
            "title" => $title ?? "",
            "message" => $message ?? "", 
            "messageError" => $messageError ?? "", 
            "functionName" => $functionName ?? "",
            "otherMessage" => $otherMessage ?? "",
        ];
    }
}
