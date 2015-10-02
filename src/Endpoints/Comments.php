<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Response\ResponseInterface;

/**
 * Class Comments
 *
 * @package  Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Comments+REST
 *
 * @method ResponseInterface analyzeMediaItem(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.analyzeMediaItem+REST
 * @method ResponseInterface deleteComment(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.deleteComment+REST
 * @method ResponseInterface deleteModerators(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.deleteModerators+REST
 * @method ResponseInterface flagComment(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.flagComment+REST
 * @method ResponseInterface getCategories(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getCategories+REST
 * @method ResponseInterface getCategoryInfo(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getCategoryInfo+REST
 * @method ResponseInterface getComments(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getComments+REST
 * @method ResponseInterface getModerators(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getModerators+REST
 * @method ResponseInterface getRelatedUsers(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getRelatedUsers+REST
 * @method ResponseInterface getStreamInfo(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getStreamInfo+REST
 * @method ResponseInterface getTopRatedStreams(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getTopRatedStreams+REST
 * @method ResponseInterface getTopStreams(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getTopStreams+REST
 * @method ResponseInterface getUserComments(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getUserComments+REST
 * @method ResponseInterface getUserHighlighting(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getUserHighlighting+REST
 * @method ResponseInterface getUserOptions(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.getUserOptions+REST
 * @method ResponseInterface highlightUser(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.highlightUser+REST
 * @method ResponseInterface moveComments(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.moveComments+REST
 * @method ResponseInterface postComment(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.postComment+REST
 * @method ResponseInterface setCategoryInfo(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.setCategoryInfo+REST
 * @method ResponseInterface setModerators(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.setModerators+REST
 * @method ResponseInterface setStreamInfo(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.setStreamInfo+REST
 * @method ResponseInterface setUserOptions(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.setUserOptions+REST
 * @method ResponseInterface subscribe(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.subscribe+REST
 * @method ResponseInterface unsubscribe(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.unsubscribe+REST
 * @method ResponseInterface updateComment(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.updateComment+REST
 * @method ResponseInterface vote(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/comments.vote+REST
 */
class Comments extends Client
{
}
