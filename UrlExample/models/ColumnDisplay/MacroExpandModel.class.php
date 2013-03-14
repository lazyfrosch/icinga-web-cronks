<?php
/*
 * Copyright (C) 2013 NETWAYS GmbH, http://netways.de
 * Markus Frosch <markus.frosch@netways.de>
 *
 * This software is licensed under the terms of the
 *             GNU Open Source GPL 3.0
 * license.
 *
 * This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program. If not, see <http://www.gnu.org/licenses/gpl.html>.
 */

class UrlExample_ColumnDisplay_MacroExpandModel extends CronksBaseModel implements AgaviISingletonModel {

    private static $macro_map = array(
        "HOSTNAME" => "HOST_NAME",
        "HOSTALIAS" => "HOST_ALIAS",
        "HOSTDISPLAYNAME" => "HOST_DISPLAY_NAME",
        "SERVICEDESC" => "SERVICE_NAME",
        "SERVICEDISPLAYNAME" => "SERVICE_DISPLAY_NAME",
        "INSTANCENAME" => "INSTANCE_NAME",
    );

    public function replaceMacros($val, AgaviParameterHolder $method_params, AgaviParameterHolder $row) {

        $matches = array();
        if (preg_match_all('#\$((?:HOST|SERVICE)[A-Z]+)\$#i', $val, $matches, PREG_SET_ORDER)) {
            $parameters = $row->getParameters();
            $replacement = $val;

            foreach ($matches as $match) {
                $var = $match[1];
                $match = $match[0];

                // do I have you in my map
                if (isset(self::$macro_map[$var])) {
                    $name = self::$macro_map[$var];
                    // and is there a parameter named like this
                    if (isset($parameters[$name])) {
                        $replacement = str_replace($match, $parameters[$name], $replacement);
                    }
                }
            }
            return $replacement;
        }
        else {
            return $val;
        }
    }

}

# vi: expandtab ts=4 sw=4:
