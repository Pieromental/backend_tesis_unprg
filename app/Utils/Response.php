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
        ];
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public static function response(
        ?int $code,
        ?string $title = "",
        ?string $message = "",
        ?string $otherMessage = "",
        ?array $data = [],
        ?array $otherData = [],
        ?array $filter = []
    ): array {
        return [
            "status" => $code == 200,
            "code" => $code ?? 400,
            "data" => $data ?? [],
            "otherData" => $otherData ?? [],
            "filter" => $filter ?? [],
            "title" => $title ?? "",
            "message" => $message ?? "",
            "otherMessage" => $otherMessage ?? "",
        ];
    }
}
