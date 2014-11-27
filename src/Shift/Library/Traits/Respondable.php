<?php
namespace Tectonic\Shift\Library\Traits;

use Request;
use View;

trait Respondable
{
    /**
     * Respond with the the $data array for JSON, a partial of the view for PJAX requests,
     * or the full layout render if it's a full page request.
     *
     * @param string $view
     * @param array $data
     */
    protected function respond($view, array $data = [])
    {
        if (Request::wantsJson()) {
            return $data;
        }

        if ($this->isPjax()) {
            return View::make($view, $data);
        }

        $this->layout->main = View::make($view, $data);
    }

    /**
     * Determines whether or not the request is a PJAX request.
     *
     * @return bool
     */
    protected function isPjax()
    {
        return Request::header('X-PJAX') === 'true';
    }

    /**
     * Returns true if the request is for the full page.
     *
     * @return bool
     */
    protected function isFullPage()
    {
        return !Request::wantsJson() && !$this->isPjax();
    }
}
 