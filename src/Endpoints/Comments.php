<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Model\ModelInterface;

/**
 * Class Comments
 *
 * @package Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Comments+REST
 *
 * @method ModelInterface analyzeMediaItem(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.analyzeMediaItem+REST
 * @method ModelInterface deleteComment(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.deleteComment+REST
 * @method ModelInterface deleteModerators(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.deleteModerators+REST
 * @method ModelInterface flagComment(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.flagComment+REST
 * @method ModelInterface getCategories(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getCategories+REST
 * @method ModelInterface getCategoryInfo(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getCategoryInfo+REST
 * @method ModelInterface getComments(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getComments+REST
 * @method ModelInterface getModerators(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getModerators+REST
 * @method ModelInterface getRelatedUsers(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getRelatedUsers+REST
 * @method ModelInterface getStreamInfo(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getStreamInfo+REST
 * @method ModelInterface getTopRatedStreams(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getTopRatedStreams+REST
 * @method ModelInterface getTopStreams(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getTopStreams+REST
 * @method ModelInterface getUserComments(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getUserComments+REST
 * @method ModelInterface getUserHighlighting(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getUserHighlighting+REST
 * @method ModelInterface getUserOptions(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.getUserOptions+REST
 * @method ModelInterface highlightUser(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.highlightUser+REST
 * @method ModelInterface moveComments(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.moveComments+REST
 * @method ModelInterface postComment(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.postComment+REST
 * @method ModelInterface setCategoryInfo(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.setCategoryInfo+REST
 * @method ModelInterface setModerators(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.setModerators+REST
 * @method ModelInterface setStreamInfo(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.setStreamInfo+REST
 * @method ModelInterface setUserOptions(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.setUserOptions+REST
 * @method ModelInterface subscribe(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.subscribe+REST
 * @method ModelInterface unsubscribe(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.unsubscribe+REST
 * @method ModelInterface updateComment(array $params) @link
 *         http://developers.gigya.com/display/GD/comments.updateComment+REST
 * @method ModelInterface vote(array $params) @link http://developers.gigya.com/display/GD/comments.vote+REST
 */
class Comments extends Client
{}
