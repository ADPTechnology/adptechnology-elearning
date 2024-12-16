<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Publishing;
use App\Models\SliderImage;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $service)
    {
        $this->settingsService = $service;
    }

    public function index(Request $request)
    {
        $sliderImages = SliderImage::with('file')
            ->orderBy('order', 'ASC')
            ->get();

        $banners = Publishing::where('type', 'BANNER')
            ->with('file')
            ->orderBy('publishing_order', 'ASC')
            ->get();

        $news = Publishing::where('type', 'NEWS')
            ->with('file')
            ->orderBy('publishing_order', 'ASC')
            ->get();

        $principalBanners = Publishing::where('type', 'PRINCIPALBANNER')
            ->with('file')
            ->orderBy('publishing_order', 'ASC')
            ->get();

        $config = Config::first();

        if (!$config) {

            $config = Config::create([
                'whatsapp_number' => env('WSP_CONTACT_NUMBER'),
                'whatsapp_message' => env('WSP_CONTACT_MESSAGE'),
                'email' => 'contacto@ejemplo.com',
                'address' => 'Calle Falsa 123, Lima, Perú'
            ]);
        }

        return view('admin.settings.index', compact(
            'sliderImages',
            'banners',
            'news',
            'config',
            'principalBanners'
        ));
    }

    public function updateConfig(Request $request, Config $config)
    {
        $data = $request->all();

        $success = $config->update($data);

        $html = view('admin.settings.partials._form_config_edit', compact('config'))->render();


        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html
        ]);
    }

    public function updateGroup(Request $request, Config $config)
    {
        $data = $request->all();

        $success = $config->update($data);

        $html = view('admin.settings.partials._group', compact('config'))->render();

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html
        ]);
    }


    public function updateLogo(Request $request, Config $config)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $success = $this->settingsService->updateLogo($request, $config, $storage);

        $html = view('admin.settings.partials.components._logo_list', compact('config'))->render();
        $html_sidebar = view('admin.common.partials.components._logo_content')->render();

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success',
            $success,
            'message' => $message,
            'html' => $html,
            'html_sidebar' => $html_sidebar
        ]);
    }

    public function destroyLogo(Config $config)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $success = $this->settingsService->destroyLogo($config, $storage);

        $html = view('admin.settings.partials.components._logo_list', compact('config'))->render();

        $html_sidebar = view('admin.common.partials.components._logo_content')->render();

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
            'html_sidebar' => $html_sidebar
        ]);
    }

    public function destroyBackground(Config $config)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $success = $this->settingsService->destroyBackground($config, $storage);

        $html = view('admin.settings.partials.components._background', compact('config'))->render();

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
        ]);
    }

    public function updateBackground(Request $request, Config $config)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $success = $this->settingsService->updateBackground($request, $config, $storage);

        $html = view('admin.settings.partials.components._background', compact('config'))->render();

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
        ]);
    }


    // 


    public function updatePrivacy(Request $request, Config $config)
    {

        $success = true;

        $data = $request->all();

        $config->update([
            'privacy_policies' => $data['privacy_policies']
        ]);

        $html = view('admin.documentation.partials._form_privacy-policies', compact('config'))->render();

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
        ]);
    }

    public function updateTerms(Request $request, Config $config)
    {

        $success = true;

        $data = $request->all();

        $config->update([
            'terms_conditions' => $data['terms_conditions']
        ]);

        $html = view('admin.documentation.partials._form_terms-conditions', compact('config'))->render();

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
        ]);
    }

    public function updateData(Request $request, Config $config)
    {

        $success = true;

        $data = $request->all();

        $config->update([
            'data_deletion' => $data['data_deletion']
        ]);

        $html = view('admin.documentation.partials._form_data-deletion', compact('config'))->render();

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
        ]);
    }
}
