<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (auth()->check() && auth()->user()->designation !== $role) {

            //return response()->json(auth()->user()->designation, 200);
            if(auth()->user()->designation == "Supervisor"){
                return redirect()->route('user.dashboard');
            }elseif(auth()->user()->designation == "SuperAdmin"){
                return redirect()->route('super.admin.dashboard');
            }elseif(auth()->user()->designation == "Agents"){
                return redirect()->route('agent.user.dashboard');
            }
            Toastr::error('403, You do not have permission to access this page.', 'Error', ["positionClass" => "toast-top-center"]);
            redirect()->back();
            //abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
