<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $creator->name }} — CV</title>
</head>
<body style="margin:0;padding:0;">
    @include(\App\Support\CvTemplates::view($template), [
        'creator' => $creator,
        'apps' => $apps,
        'media' => $media,
    ])
</body>
</html>
