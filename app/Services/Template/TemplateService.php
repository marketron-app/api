<?php

namespace App\Services\Template;

use App\Http\Requests\Admin\Template\StoreTemplateRequest;
use App\Models\Template;
use Ramsey\Uuid\Uuid;

class TemplateService
{
    public function index($page = 1, $perPage = 20)
    {
        return Template::query()->paginate($perPage, ['*'], 'page', $page);
    }

    public function storeTemplate(StoreTemplateRequest $request)
    {
        $originalImageFileName = Uuid::uuid4().'.png';
        $originalImage = $request->file('image')->storeAs('original-images', $originalImageFileName);

        $template = Template::query()->create([
            'identifier' => $request->validated('identifier'),
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'coordinates' => $request->validated('screenshotCoordinates'),
            'screenshot_width' => $request->validated('screenshotWidth'),
            'screenshot_height' => $request->validated('screenshotHeight'),
            'raw_data' => ['screenshotCoordinates' => $request->validated('screenshotCoordinates'), 'cutoutCoordinates' => $request->validated('cutoutCoordinates')],
            'original_image' => $originalImage,
        ]);

        return $template;
    }
}
